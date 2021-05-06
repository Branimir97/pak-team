<?php


namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class APIService
{
    private $client;

    public function __construct(HttpClientInterface $client) {
        $this->client = $client;
    }

    public function fetchEuroCourse() {
        $response  = $this->client->request(
            'GET',
            'https://api.exchangeratesapi.io/latest'
        );

        $content = $response->getContent();
        $content = $response->toArray();

        return $content['rates']['HRK'];
    }
}