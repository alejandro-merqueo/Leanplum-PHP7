<?php
namespace Leanplum;

use Leanplum\Message\Request\RequestAbstract;

/**
 * Represents an Leanplum client.
 */
interface LeanplumClientInterface
{
    /**
     * @param $method
     * @param Message\Request\RequestAbstract|array $arguments
     * @return \Guzzle\Http\Message\Response
     */
    public function __call($method, array $arguments);
}
