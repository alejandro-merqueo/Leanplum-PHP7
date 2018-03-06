<?php

namespace LeanplumTests;

use Leanplum\LeanplumClient;
use PHPUnit_Framework_TestCase;

class TestCase extends PHPUnit_Framework_TestCase
{
    const DEV_KEY = 'dev_FUEQ91';
    const APP_ID = 'app_tVpD';

    /**
     * @return LeanplumClient
     */
    protected function getLeanplumClient()
    {
        return new LeanplumClient(self::DEV_KEY, self::APP_ID);
    }
}
