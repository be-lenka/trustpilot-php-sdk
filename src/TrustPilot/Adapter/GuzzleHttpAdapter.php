<?php

namespace TrustPilot\Adapter;

use TrustPilot\Exception\HttpException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;

class GuzzleHttpAdapter implements AdapterInterface
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Response|ResponseInterface
     */
    protected $response;

    /**
     * @var ClientInterface
     */
    protected $endpoint;

    /**
     * @param string               $token
     * @param ClientInterface|null $client
     */
    public function __construct($headers, $endpoint = '', ClientInterface $client = null)
    {
        $this->endpoint = $endpoint;
        $this->client = $client ?: new Client($headers);
    }

    /**
     * {@inheritdoc}
     */
    public function get($url, $options = array())
    {
        try {
            $this->response = $this->client->get($this->endpoint . $url, $options);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            $this->handleError();
        }

        return $this->response->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function delete($url)
    {
        try {
            $this->response = $this->client->delete($this->endpoint . $url);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            $this->handleError();
        }

        return $this->response->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function put($url, $content = '')
    {
        try {
            $this->response = $this->client->put($this->endpoint . $url, $content);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            $this->handleError();
        }

        return $this->response->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function post($url, $content = '')
    {
        try {
            $this->response = $this->client->post($this->endpoint . $url, $content);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            $this->handleError();
        }

        return $this->response->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function getLatestResponseHeaders()
    {
        if (null === $this->response) {
            return;
        }

        return [
            'reset' => (int) (string) $this->response->getHeader('RateLimit-Reset'),
            'remaining' => (int) (string) $this->response->getHeader('RateLimit-Remaining'),
            'limit' => (int) (string) $this->response->getHeader('RateLimit-Limit'),
        ];
    }

    /**
     * @throws HttpException
     */
    protected function handleError()
    {

        $body = (string) $this->response->getBody();

        $code = (int) $this->response->getStatusCode();

        $content = json_decode($body);

        throw new HttpException(isset($content) ? print_r($content, true) : 'Request not processed.', $code);
    }
}
