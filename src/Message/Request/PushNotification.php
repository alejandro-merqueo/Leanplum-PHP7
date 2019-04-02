<?php

namespace Leanplum\Message\Request;

use Leanplum\Exceptions\InvalidArgumentException;

/**
 * Push notification sent by Leanplum.
 *
 * @package Leanplum\Message\Request
 */
class PushNotification extends RequestAbstract
{
    const ACTION = 'sendMessage';

    // Create disposition options
    const CREATE_IF_NEEDED = 'CreateIfNeeded';
    const CREATE_NEVER = 'CreateNever';

    /**
     * @var integer
     */
    protected $userId;

    /**
     * @var string
     */
    protected $deviceId;

    /**
     * @var string
     */
    protected $action = self::ACTION;

    /**
     * @var array
     */
    protected $values = [];

    /**
     * @var integer
     */
    protected $messageId;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var string
     */
    protected $createDisposition = self::CREATE_NEVER;

    /**
     * @var bool
     */
    protected $force = false;

    /**
     * PushNotification constructor.
     *
     * @param int $messageId
     * @param array $values
     */
    public function __construct($messageId, array $values)
    {
        $this->messageId = $messageId;
        $this->values = $values;
    }

    /**
     * Format entity
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public function format()
    {
        $userId = $this->getUserId();
        $deviceId = $this->getDeviceId();

        if (empty($userId) && empty($deviceId)) {
            throw new InvalidArgumentException('"userId" or "deviceId" must be provided.');
        }

        $values = $this->getValues();
        $values = empty($values) ? new \StdClass() : $values;

        $content = [
            'action' => $this->getAction(),
            'values' => $values,
            'messageId' => $this->getMessageId(),
            'createDisposition' => $this->getCreateDisposition(),
            'force' => $this->getForce(),
        ];

        if (!empty($userId)) {
            $content['userId'] = $userId;
        }

        if (!empty($deviceId)) {
            $content['deviceId'] = $deviceId;
        }

        return $content;
    }
}
