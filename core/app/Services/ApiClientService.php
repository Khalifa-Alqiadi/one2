<?php

namespace App\Services;

class ApiClientService
{
    /**
     * Perform a GET request using cURL and return structured result.
     *
     * @param string $url
     * @return array
     */
    public function curlGet(string $url): array
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);

        $res = curl_exec($ch);

        if ($res === false) {
            $err = curl_error($ch);
            curl_close($ch);
            return ['ok' => false, 'error' => $err];
        }

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $json = json_decode($res, true);

        // Consider it an error only when HTTP code indicates an error or API returns errors
        if ($code >= 400) {
            $msg = data_get($json, 'message') ?: 'HTTP error';
            return ['ok' => false, 'http' => $code, 'error' => $msg, 'json' => $json];
        }

        if (data_get($json, 'errors')) {
            return ['ok' => false, 'http' => $code, 'error' => 'API errors', 'json' => $json];
        }

        return ['ok' => true, 'http' => $code, 'json' => $json];
    }
}
