<?php

namespace TrustPilot\Api;

use TrustPilot\TrustPilot;

abstract class AbstractApi
{

    /**
     * @var string
     */
    protected $service;

    /**
     * @var string
     */
    protected $return;

    /**
     * @var string
     */
    protected $api;

    /**
     * @var string
     */
    protected $client;

    /**
     * Request data when doing create or update method
     *
     * @var string
     */


    public function __construct(TrustPilot $client)
    {
        $this->client = $client;
        $this->api = $client->getClient();
    }
}
