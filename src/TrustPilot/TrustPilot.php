<?php

namespace TrustPilot;

use stdClass;
use TrustPilot\Adapter\AdapterInterface;
use TrustPilot\Adapter\GuzzleHttpAdapter;
use TrustPilot\Api\Authorize;
use TrustPilot\Api\Categories;
use TrustPilot\Api\Consumer;
use TrustPilot\Api\Invitation;
use TrustPilot\Api\Resources;
use TrustPilot\Api\BusinessUnit;
use TrustPilot\Api\ProductReviews;
use TrustPilot\Api\ServiceReviews;

class TrustPilot
{
    /**
     * @var string
     */
    private const ENDPOINT = 'https://api.trustpilot.com/v1/';

    protected string $endpoint;

    protected string $secret;

    protected string $apiKey;

    protected ?AdapterInterface $adapter = null;

    protected stdClass $token;

    /**
     * @param string $apiKey
     * @param string $secret
     * @param string|null $endpoint
     */
    public function __construct(string $apiKey, string $secret, string $endpoint = null)
    {
        $this->apiKey = $apiKey;
        $this->secret = $secret;
        $this->endpoint = $endpoint ?: static::ENDPOINT;
    }

    /**
     * Set the access token
     *
     * @param stdClass $token
     * @return void
     */
    public function setToken(stdClass $token): void
    {
        $this->token = $token;
        $auth = $this->authorize();
        $auth->setToken($this->token);
    }

    /**
     * get the access token
     * @return stdClass
     */
    public function getToken(): stdClass
    {
        return $this->token;
    }

    protected function setAdapter(AdapterInterface $adapter = null, array $headers = []): TrustPilot
    {
        if (is_null($adapter)) {
            $this->client = new GuzzleHttpAdapter($headers, $this->endpoint);
            return $this;
        }
        $this->client = new $adapter($headers, $this->endpoint);
        return $this;
    }

    /**
     * Set adapter to use token from Oauth
     * @return void
     */
    protected function setAdapterWithToken(): void
    {
        $headers = [
            'headers' =>
                [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => 'Bearer ' . $this->token->access_token,
                ]
        ];
        $this->setAdapter($this->adapter, $headers);
    }

    /**
     * Set adapter to use API key
     * @return void
     */
    protected function setAdapterWithApikey(): void
    {
        $headers = [
            'headers' =>
            ['apikey' => $this->apiKey]
        ];
        $this->setAdapter($this->adapter, $headers);
    }

    public function getClient(): AdapterInterface
    {
        return $this->client;
    }

    public function authorize(): Authorize
    {
        $headers = [
            'headers' =>
                [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => 'Basic ' . base64_encode($this->apiKey . ':' . $this->secret)
                ]
        ];
        $this->setAdapter($this->adapter, $headers);
        return new Authorize($this);
    }

    public function businessUnit(): BusinessUnit
    {
        $this->setAdapterWithApikey();
        return new BusinessUnit($this);
    }

    public function categories(): Categories
    {
        $this->setAdapterWithApikey();
        return new Categories($this);
    }

    public function consumer(): Consumer
    {
        $this->setAdapterWithApikey();
        return new Consumer($this);
    }

    public function resources(): Resources
    {
        $this->setAdapterWithApikey();
        return new Resources($this);
    }

    public function invitation(): Invitation
    {
        $this->endpoint = 'https://invitations-api.trustpilot.com/v1/';
        $this->setAdapterWithToken();
        return new Invitation($this);
    }

    public function productReviews(): ProductReviews
    {
        $this->setAdapterWithToken();
        return new ProductReviews($this);
    }

    public function serviceReviews(): ServiceReviews
    {
        $this->setAdapterWithToken();
        return new ServiceReviews($this);
    }
}
