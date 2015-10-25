<?php

/*
 * This file is part of the MobileNotifBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LinkValue\MobileNotifBundle\Client;

use Psr\Log\LoggerInterface;
use LinkValue\MobileNotif\Exception\PushException;
use LinkValue\MobileNotif\Model\Message;
use LinkValue\MobileNotifBundle\Profiler\NotifProfiler;
use LinkValue\MobileNotif\Client\AppleClient as BaseAppleClient;

/**
 * MobileNotifBundle
 * Apple Push Notification services implementation.
 *
 * @package MobileNotifBundle
 * @author  Jamal Youssefi <jamal.youssefi@gmail.com>
 * @author  Valentin Coulon <valentin.c0610@gmail.com>
 */
class AppleClient implements BaseAppleClient
{
    /**
     * @var NotifProfiler $notifProfiler
     */
    protected $notifProfiler;

    /**
     * ApplePushNotificationClient constructor.
     *
     * @param LoggerInterface $logger
     * @param NotifProfiler $notifProfiler
     */
    public function __construct(LoggerInterface $logger, NotifProfiler $notifProfiler)
    {
        parent::__construct($logger);

        $this->notifProfiler = $notifProfiler;
    }

    /**
     * Push a notification to a mobile client.
     *
     * @param Message $message
     */
    public function push(Message $message)
    {
        try {

            $profilingEvent = $this->notifProfiler->startProfiling('IOS : ' . $message->getContent());

            $stream = $this->getStreamSocketClient();

            $payload = $this->getPayload($message);

            $binaryMessage = sprintf('%s%s%s%s%s', chr(0), pack('n', 32), pack('H*', str_replace(' ', '', $message->getToken())), pack('n', strlen($payload)), $payload);

            $this->logger->info('Sending message to Apple push notification server', array(
                'deviceToken' => $message->getToken(),
                'payload' => $payload,
            ));

            fwrite($stream, $binaryMessage, strlen($binaryMessage));

            fclose($stream);

            $this->notifProfiler->stopProfiling($profilingEvent, array(
                'error' => false,
                'error_message' => null,
            );

        } catch (\exception $e) {

            $this->notifProfiler->stopProfiling($profilingEvent, array(
                'error' => true,
                'error_message' => $e->getMessage(),
            );

            throw $e;
        }
    }
}
