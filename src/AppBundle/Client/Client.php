<?php

namespace AppBundle\Client;

use GuzzleHttp\Client as GuzzleClient;

class Client
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var GuzzleClient
     */
    private $client;

    /**
     * @param array $serverConfig
     */
    public function __construct(array $serverConfig)
    {
        $this->baseUrl = $serverConfig['host'];
        $this->port = $serverConfig['port'];
        $this->username = $serverConfig['username'];
        $this->password = $serverConfig['password'];

        $this->client = new GuzzleClient;
    }

    /**
     * @param string $uri
     * @param array $options
     *
     * @return array
     */
    public function get($uri, $options = [])
    {
        return json_decode($this->getPlain($uri, $options), true);
    }

    /**
     * @param string $uri
     * @param array $options
     *
     * @return string
     */
    public function getPlain($uri, $options = [])
    {
        $this->resolveHeaders($options);

        return $this
            ->client
            ->get(sprintf('%s:%d/%s', $this->baseUrl, $this->port, $uri), $options)
            ->getBody()
            ->getContents()
        ;
    }

    /**
     * @param string $uri
     * @param array $options
     *
     * @return array
     */
    public function post($uri, $options = [])
    {
        $this->resolveHeaders($options);
        $response = $this
            ->client
            ->post(sprintf('%s:%d/%s', $this->baseUrl, $this->port, $uri), $options)
            ->getBody()
            ->getContents()
        ;

        return json_decode($response, true);
    }

    /**
     * @param array $options
     */
    private function resolveHeaders(array &$options)
    {
        $options['headers'] = array_merge([
            'X-USERNAME' => $this->username,
            'X-PASSWORD' => $this->password
        ], isset($options['headers']) ? $options['headers'] : []);
    }
}
