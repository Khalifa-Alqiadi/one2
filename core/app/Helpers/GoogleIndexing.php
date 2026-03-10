<?php
namespace App\Helpers;

use Google\Client;
use Google\Service\Indexing;
use Exception;
use Illuminate\Support\Facades\Storage;

class GoogleIndexing
{
    protected $client;
    protected $indexingService;
    protected $serviceAccountPath;

    public function __construct()
    {
        $this->serviceAccountPath = $this->getServiceAccountFilePath();

        if (!file_exists($this->serviceAccountPath)) {
            throw new Exception("Google service account file not found at: {$this->serviceAccountPath}");
        }

        $this->client = new Client();
        $this->client->setAuthConfig($this->serviceAccountPath);
        $this->client->addScope(Indexing::INDEXING);
        $this->client->useApplicationDefaultCredentials();

        $this->indexingService = new Indexing($this->client);
    }

    protected function getServiceAccountFilePath(): string
    {
        $instant_index_file = Helper::GeneralWebmasterSettings('instant_index_file');
        if ($instant_index_file != "") {
            $instant_index_file_path = (trim(env('LOCAL_UPLOADS_PATH')) !== '' ? env('LOCAL_UPLOADS_PATH') : public_path('uploads'))."/settings/".$instant_index_file;
            if (file_exists($instant_index_file_path)) {
                return $instant_index_file_path;
            }
        }
        throw new Exception("Google service account (.json) file not founded");
    }

    public function updateUrl(string $url): array
    {
        return $this->sendRequest($url, 'URL_UPDATED');
    }

    public function deleteUrl(string $url): array
    {
        return $this->sendRequest($url, 'URL_DELETED');
    }

    public function updateUrls(array $urls): array
    {
        return $this->bulkRequest($urls, 'URL_UPDATED');
    }

    public function deleteUrls(array $urls): array
    {
        return $this->bulkRequest($urls, 'URL_DELETED');
    }

    protected function sendRequest(string $url, string $type): array
    {
        try {
            $publishUrl = new Indexing\UrlNotification();
            $publishUrl->setUrl($url);
            $publishUrl->setType($type);

            $result = $this->indexingService->urlNotifications->publish($publishUrl);
            return ['success' => true, 'response' => $result];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    protected function bulkRequest(array $urls, string $type): array
    {
        $results = [];

        foreach ($urls as $url) {
            $results[$url] = $this->sendRequest($url, $type);
        }

        return $results;
    }

    public function addOrRemove($URLs = [], $Remove = 0): array
    {
        try {
            if ($URLs) {
                $indexer = new GoogleIndexing();
                if ($Remove) {
                    $response = $indexer->deleteUrls($URLs);
                } else {
                    $response = $indexer->updateUrls($URLs);
                }

                $successCount = 0;
                $failedUrls = [];

                $i = 0;
                $more = 0;
                foreach ($response as $url => $result) {
                    if (isset($result['success']) && $result['success']) {
                        $successCount++;
                    } else {
                        if ($i < 5) {
                            $failedUrls[] = '<strong>'.$url.'</strong>'.'<br>'.(substr($result['error'], 0,
                                    300).".." ?? 'Unknown error').'<br>';
                        } else {
                            $more++;
                        }
                    }
                    $i++;
                }

                if (count($failedUrls) === 0) {
                    return [
                        "status" => "doneMessage",
                        "message" => __("backend.instantIndexingSuccess"),
                    ];
                } else {
                    $message = __('backend.instantIndexingSummary', [
                        'successCount' => $successCount,
                        'totalCount' => count($URLs),
                    ]);
                    $message .= implode('<br>', $failedUrls);
                    if ($more) {
                        $message .= "<br>"."<strong>+{$more} ".__("backend.errors")."</strong>";
                    }
                    return [
                        "status" => "errorMessage",
                        "message" => $message,
                    ];
                }
            }
        } catch (Exception $e) {

        }
        return [
            "status" => "errorMessage",
            "message" => __("backend.error"),
        ];
    }
}

?>
