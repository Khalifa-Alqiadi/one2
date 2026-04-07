<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class FetchCommentaryService
{
    private ApiClientService $apiClient;

    public function __construct(ApiClientService $apiClient)
    {
        $this->apiClient = $apiClient;
    }
    public function getLiveCommentary(int $fixtureId, string $locale = 'ar')
    {
        $token = config('services.SPORTMONKS_TOKEN');

        $url = "https://api.sportmonks.com/v3/football/commentaries/fixtures/{$fixtureId}"
            . "?api_token={$token}&locale={$locale}";

        $res = $this->apiClient->curlGet($url);
        if (!data_get($res, 'ok')) return null;

        $rows = data_get($res, 'json.data', []);




        return [
            'ok' => true,
            'data' => collect($rows)
                ->map(function ($row) {
                    return [
                        'id' => data_get($row, 'id'),
                        'minute' => data_get($row, 'minute'),
                        'extra_minute' => data_get($row, 'extra_minute'),
                        'comment' => data_get($row, 'comment'),
                        'is_goal' => str_contains(strtolower(data_get($row, 'comment')), 'goal'),
                    ];
                })
                ->sortByDesc('minute') // 🔥 ترتيب تنازلي
                ->values()
                ->all(),
        ];
    }
}
?>
