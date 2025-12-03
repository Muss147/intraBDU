<?php
// src/Service/IpLocationService.php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class IpLocationService
{
    public function __construct(private HttpClientInterface $client) {}

    public function getLocation(string $ip): string
    {
        try {
            $response = $this->client->request('GET', "http://ip-api.com/json/{$ip}?fields=country,regionName,city");
            $data = $response->toArray();
            return "{$data['city']} - {$data['regionName']} - {$data['country']}";
        } catch (\Throwable $e) {
            return 'Inconnue';
        }
    }
}