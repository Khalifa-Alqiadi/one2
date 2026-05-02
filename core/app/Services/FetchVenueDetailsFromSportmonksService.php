<?php

namespace App\Services;

use App\Models\Venue;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class FetchVenueDetailsFromSportmonksService
{
    private string $token;
    private string $baseUrl;

    public function __construct(?string $token = null)
    {
        $this->token = $token ?: (string) config('services.SPORTMONKS_TOKEN');
        $this->baseUrl = rtrim((string) config('sportmonks.base_url', 'https://api.sportmonks.com/v3/football'), '/');
    }

    public function handle(int $venueId): Venue
    {
        $data = $this->fetchVenueDetailsFromSportmonks($venueId);

        return $this->persistVenueDetails($data['ar'], $data['en']);
    }

    public function fetchVenueDetailsFromSportmonks(int $venueId): array
    {
        return [
            'ar' => $this->fetchVenue($venueId, 'ar'),
            'en' => $this->fetchVenue($venueId, 'en'),
        ];
    }

    public function persistVenueDetails(array $arabicVenue, array $englishVenue = []): Venue
    {
        $venueId = (int) data_get($arabicVenue, 'id', data_get($englishVenue, 'id', 0));

        if ($venueId <= 0) {
            throw new RuntimeException('SportMonks venue id is missing.');
        }

        $attributes = [
            'name_ar'       => $this->firstFilled($arabicVenue, $englishVenue, 'name', ''),
            'name_en'       => $this->firstFilled($englishVenue, $arabicVenue, 'name', ''),
            'country_id'    => $this->firstFilled($arabicVenue, $englishVenue, 'country_id'),
            'city_id'       => $this->firstFilled($arabicVenue, $englishVenue, 'city_id'),
            'address'       => $this->firstFilled($arabicVenue, $englishVenue, 'address'),
            'zipcode'       => $this->firstFilled($arabicVenue, $englishVenue, 'zipcode'),
            'latitude'      => $this->firstFilled($arabicVenue, $englishVenue, 'latitude'),
            'longitude'     => $this->firstFilled($arabicVenue, $englishVenue, 'longitude'),
            'capacity'      => $this->firstFilled($arabicVenue, $englishVenue, 'capacity'),
            'image_path'    => $this->firstFilled($arabicVenue, $englishVenue, 'image_path'),
            'city_name'     => $this->firstFilled($arabicVenue, $englishVenue, 'city_name'),
            'surface'       => $this->firstFilled($arabicVenue, $englishVenue, 'surface'),
            'national_team' => (bool) $this->firstFilled($arabicVenue, $englishVenue, 'national_team', false),
        ];

        $venue = Venue::find($venueId);

        if ($venue) {
            $venue->update($attributes);

            return $venue->refresh();
        }

        return Venue::create(['id' => $venueId] + $attributes);
    }

    private function fetchVenue(int $venueId, string $locale): array
    {
        if ($venueId <= 0) {
            throw new RuntimeException('Venue id must be greater than zero.');
        }

        if ($this->token === '') {
            throw new RuntimeException('SPORTMONKS_TOKEN is missing.');
        }

        $response = Http::timeout(60)
            ->retry(3, 1000)
            ->acceptJson()
            ->get("{$this->baseUrl}/venues/{$venueId}", [
                'api_token' => $this->token,
                'locale' => $locale,
            ]);

        if (!$response->successful()) {
            throw new RuntimeException(
                "SportMonks {$locale} venue request failed. Status: {$response->status()} Body: {$response->body()}"
            );
        }

        $json = $response->json();

        if (!is_array($json)) {
            throw new RuntimeException('SportMonks returned a non-JSON venue response.');
        }

        if (data_get($json, 'errors')) {
            throw new RuntimeException('SportMonks returned venue API errors: ' . json_encode(data_get($json, 'errors')));
        }

        $venue = data_get($json, 'data', []);

        if (isset($venue[0]) && is_array($venue[0])) {
            $venue = $venue[0];
        }

        if (!is_array($venue) || (int) data_get($venue, 'id', 0) <= 0) {
            throw new RuntimeException("SportMonks venue {$venueId} was not found for locale {$locale}.");
        }

        return [
            'id' => (int) data_get($venue, 'id'),
            'country_id' => data_get($venue, 'country_id'),
            'city_id' => data_get($venue, 'city_id'),
            'name' => data_get($venue, 'name'),
            'address' => data_get($venue, 'address'),
            'zipcode' => data_get($venue, 'zipcode'),
            'latitude' => data_get($venue, 'latitude'),
            'longitude' => data_get($venue, 'longitude'),
            'capacity' => data_get($venue, 'capacity'),
            'image_path' => data_get($venue, 'image_path'),
            'city_name' => data_get($venue, 'city_name'),
            'surface' => data_get($venue, 'surface'),
            'national_team' => (bool) data_get($venue, 'national_team', false),
            'raw' => $venue,
        ];
    }

    private function firstFilled(array $primary, array $fallback, string $key, mixed $default = null): mixed
    {
        $value = data_get($primary, $key);

        if ($value !== null && $value !== '') {
            return $value;
        }

        $value = data_get($fallback, $key);

        return ($value !== null && $value !== '') ? $value : $default;
    }
}
