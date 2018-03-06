<?php

namespace LeanplumTests;

use Leanplum\Message\Request\PushNotification;

class PushNotificationTest extends TestCase
{
    const MESSAGE_ID = '6886545072652288';
    const DEVICE_ID = '63cbc52b8c19ec08';

    public function testSendPushNotification()
    {
        $notification = new PushNotification(self::MESSAGE_ID, []);
        $notification->set('deviceId', self::DEVICE_ID);
        $notification->set('createDisposition', PushNotification::CREATE_IF_NEEDED);
        $client = $this->getLeanplumClient();
        $response = $client->sendMessage($notification);
        $this->assertTrue($response->wasValid());
    }
}
