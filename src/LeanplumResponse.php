<?php

namespace Leanplum;

use Guzzle\Http\Message\Response;

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

        $response = $this->guzzleResponse->json();

        foreach ($response['response'] as $item) {
            if ($item['success'] && empty($item['error']) && empty($item['warning'])) {
                $this->validItems++;
            } else {
                $this->invalidItems++;
            }
        }

        return $this->wasValid = $this->invalidItems === 0;
    }


    /**
     * @return null|string
     */
    public function getMessage()
    {
        if ($this->wasValid()) {
            return null;
        }

        $response = $this->guzzleResponse->json();
        foreach ($response['response'] as $item) {
            if (!empty($item['error']['message'])) {
                return $item['error']['message'];
            } elseif (!empty($item['warning']['message'])) {
                return $item['warning']['message'];
            }
        }
    }
}
