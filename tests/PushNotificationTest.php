<?php

namespace LeanplumTests;

use Leanplum\Message\Request\PushNotification;

class PushNotificationTest extends TestCase
{
    const MESSAGE_ID = '5706754232287232';
    const DEVICE_ID = '73cbc52b8c19ec08';
    const USER_ID = '79431';

    public function testSendPushNotificationToDevice()
    {
        $notification = new PushNotification(self::MESSAGE_ID, []);
        $notification->set('deviceId', self::DEVICE_ID);
        $notification->set('createDisposition', PushNotification::CREATE_IF_NEEDED);
        $client = $this->getLeanplumClient();
        $response = $client->sendMessage($notification);
        $this->assertTrue($response->wasValid());
    }

    public function testSendPushNotificationToUser()
    {
        $notification = new PushNotification(self::MESSAGE_ID, []);
        $notification->set('userId', self::USER_ID);
        $notification->set('createDisposition', PushNotification::CREATE_IF_NEEDED);
        $client = $this->getLeanplumClient();
        $response = $client->sendMessage($notification);
        $this->assertEmpty($response->getMessage());
        $this->assertTrue($response->wasValid());
    }
}
