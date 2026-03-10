<?php

namespace App\Helpers\GeoIP;

use Exception;
use Torann\GeoIP\Support\HttpClient;
use Torann\GeoIP\Services\AbstractService;

class IPApiCo extends AbstractService
{
    /**
     * Http client instance.
     *
     * @var HttpClient
     */
    protected $client = 'https://ipapi.co/';

    /**
     * The "booting" method of the service.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function locate($ip)
    {
        $key = config('geoip.services.ipapico.key');
        // Get data from client
        try {
            if ($key != "") {
                $data = \Http::withHeaders([
                    'Authorization' => 'Bearer '.$key,
                ])->get($this->client.$ip."/json/");
            } else {
                $data = \Http::get($this->client.$ip."/json/");
            }
            $json = json_decode($data);
        } catch (Exception $e) {
            $json = [];
        }

        return [
            'ip' => $ip,
            'iso_code' => @$json->country_code_iso3,
            'country' => @$json->country,
            'city' => @$json->city,
            'state' => @$json->region_code,
            'state_name' => @$json->region,
            'continent' => @$json->continent_code,
            'postal_code' => @$json->postal,
            'lat' => @$json->latitude,
            'lon' => @$json->longitude,
            'timezone' => @$json->timezone,
            'currency' => @$json->currency,
            'org' => @$json->org,
        ];
    }

    /**
     * Update function for service.
     *
     * @return string
     */
    public function update()
    {
        // Optional artisan command line update method
    }
}
