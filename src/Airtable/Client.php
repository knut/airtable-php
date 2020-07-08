<?php declare(strict_types=1);

namespace Airtable;

/**
 * Airtable API Client class
 */
class Client
{
    /** @var string The Airtable API key generated from: https://airtable.com/account */
    public $apiKey;
    public $baseId;

    /** @var \GuzzleHttp\Client $httpClient */
    private $httpClient;

    private $baseUrl = 'https://api.airtable.com/v0/';

    private $headers;

    private $resources = []; // holder of resources

    public function __construct($apiKey, $baseId) {
        $this->apiKey = $apiKey;
        $this->baseId = $baseId;

        $this->headers = [
            'Authorization' => 'Bearer '.$this->apiKey,
            'Content-Type' => 'application/json',
        ];

        $this->httpClient = new \GuzzleHttp\Client([
            'headers' => $this->headers,
        ]);

    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getBaseUri()
    {
        return $this->baseUrl.$this->baseId;
    }

    public function getHttpClient()
    {
        return $this->httpClient;
    }

    public function setHttpClient($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function __get($name)
    {
        // TODO: Singleton for same resource name?
        return new \Airtable\Request($this, $name);
    }

}