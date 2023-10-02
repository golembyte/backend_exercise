<?php

namespace App\Infrastructure\Domain\Api;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ApiService
{
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = HttpClient::create([
            'base_uri' => 'https://api.punkapi.com/v2/',
        ]);
    }

    /**
     * Sends a GET request to the API and returns the response.
     *
     * @param string $endpoint The API endpoint to which the request will be made.
     * @param array $queryParams Query parameters (optional).
     *
     * @return ResponseInterface
     */
    protected function get(string $endpoint, array $queryParams = []): ResponseInterface
    {
        return $this->httpClient->request('GET', $endpoint, [
            'query' => $queryParams,
        ]);
    }
}
