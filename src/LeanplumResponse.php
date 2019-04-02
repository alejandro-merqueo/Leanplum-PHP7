<?php

namespace Leanplum;

use GuzzleHttp\Psr7\Response;

/**
 * Class LeanplumResponse
 * @package Leanplum
 */
class LeanplumResponse
{

    /**
     * @var Response
     */
    protected $guzzleResponse;

    /**
     * @var array
     */
    protected $jsonResponse;

    /**
     * @var int
     */
    protected $invalidItems = 0;

    /**
     * @var int
     */
    protected $validItems = 0;

    /**
     * @var boolean
     */
    protected $wasValid;

    /**
     * LeanplumResponse constructor.
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->guzzleResponse = $response;
    }

    /**
     * @return bool
     */
    public function wasValid()
    {
        if ($this->wasValid !== null) {
            return $this->wasValid;
        }

        $response = json_decode($this->guzzleResponse->getBody(), true);
        foreach ($response['response'] as $item) {
            if ($item['success'] && empty($item['error']) && empty($item['warning'])) {
                $this->validItems++;
            } else {
                $this->invalidItems++;
            }
        }

        $this->wasValid = $this->invalidItems === 0;

        return $this->wasValid;
    }


    /**
     * @return null|string
     */
    public function getMessage()
    {
        if ($this->wasValid()) {
            return null;
        }

        $response = json_decode($this->guzzleResponse->getBody(), true);
        var_dump($response);exit;
        foreach ($response['response'] as $item) {
            if (!empty($item['error']['message'])) {
                return $item['error']['message'];
            } elseif (!empty($item['warning']['message'])) {
                return $item['warning']['message'];
            }
        }
    }
}
