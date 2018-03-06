<?php

namespace Leanplum;

use Guzzle\Http\Client;
use Leanplum\Message\Request\PushNotification;
use Leanplum\Message\Request\RequestAbstract;
use Leanplum\Message\Response;

/**
 * Default AWS client implementation
 *
 * @method LeanplumResponse sendMessage(PushNotification $pushnotification)
 */
class LeanplumClient implements LeanplumClientInterface
{
    /** @var string */
    const LEANPLUM_URL = 'https://www.leanplum.com/api?';

    /** @var string */
    const API_VERSION = "1.0.6";

    /**
     * @var string
     */
    protected $clientKey = '';

    /**
     * @var string
     */
    protected $appId = '';

    /**
     * @var string
     */
    protected $apiVersion = self::API_VERSION;

    /**
     * @var Client|null
     */
    protected $client = null;

    /**
     * @var Client|null
     */
    protected $devMode;

    /**
     * @var Response
     */
    protected $response;

    /**
     * LeanplumClient constructor.
     * @param string $clientKey
     * @param string $appId
     * @param string $apiVersion
     * @param boolean $devMode
     */
    public function __construct($clientKey = null, $appId = null, $apiVersion = null, $devMode = true)
    {
        if (null !== $clientKey) {
            $this->setClientKey($clientKey);
        }
        if (null !== $appId) {
            $this->setAppId($appId);
        }
        if (null !== $apiVersion) {
            $this->setApiVersion($apiVersion);
        }

        $this->devMode = $devMode;
    }

    /**
     * @param string $clientKey
     * @return $this
     */
    public function setClientKey($clientKey)
    {
        $this->clientKey = $clientKey;
        return $this;
    }

    /**
     * @param string $appId
     * @return $this
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;
        return $this;
    }

    /**
     * @param boolean $devMode
     * @return $this
     */
    public function setDevMode($devMode)
    {
        $this->devMode = $devMode;
        return $this;
    }

    /**
     * @param string $apiVersion
     * @return $this
     */
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion;
        return $this;
    }

    /**
     * @param $method
     * @param \Leanplum\Message\Request\RequestAbstract|array $arguments
     * @return LeanplumResponse
     */
    public function __call($method, array $arguments)
    {
        $message = array_pop($arguments);
        if (!in_array($method, $this->validMethods())) {
            throw new \InvalidArgumentException("Invalid method");
        }

        $uriParams = [
            'appId' => $this->appId,
            'action' => $message->getAction(),
            'clientKey' => $this->clientKey,
            'apiVersion' => $this->apiVersion,
            'devMode' => $this->devMode,
            'time' => time(),
        ];

        $url = self::LEANPLUM_URL . http_build_query($uriParams);
        $request = $this->getClient()->post($url, ['Content-Type' => 'application/json']);

        $request->setBody(json_encode($message->format()));
        $this->response = new LeanplumResponse($request->send());

        return $this->response;
    }

    /**
     * Get client
     * @return Client
     */
    protected function getClient()
    {
        if (null === $this->client) {
            $this->client = new Client();
        }

        return $this->client;
    }

    /**
     * @return array
     */
    protected function validMethods()
    {
        return ['track', 'start', 'resumeSession', 'sendMessage'];
    }
}
