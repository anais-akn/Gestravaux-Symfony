<?php
namespace App\Service;
 
use Symfony\Contracts\HttpClient\HttpClientInterface;
 
class GeocodeService
{
    private string $apiKey;
    private HttpClientInterface $httpClient;
 
    public function __construct(string $googleMapsApiKey, HttpClientInterface $httpClient)
    {
        $this->apiKey = $googleMapsApiKey;
        $this->httpClient = $httpClient;
    }
 
    /**
     * Convertit une adresse en coordonnées GPS
     */
    public function geocodeAddress(string $address): ?array
    {
        try {
            $response = $this->httpClient->request('GET', 'https://maps.googleapis.com/maps/api/geocode/json', [
                'query' => [
                    'address' => $address,
                    'key' => $this->apiKey,
                ]
            ]);
 
            $data = $response->toArray();
 
            if ($data['status'] === 'OK' && count($data['results']) > 0) {
                $location = $data['results'][0]['geometry']['location'];
                return [
                    'lat' => $location['lat'],
                    'lng' => $location['lng'],
                ];
            }
 
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}