<?php

namespace LeanplumTests;

use Leanplum\LeanplumClient;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    const DEV_KEY = 'dev_key';
    const APP_ID = 'app_id';

    /**
     * @return LeanplumClient
     */
    protected function getLeanplumClient()
    {
        return new LeanplumClient(self::DEV_KEY, self::APP_ID);
    }
}
