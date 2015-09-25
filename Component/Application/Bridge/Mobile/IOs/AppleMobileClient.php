<?php

namespace PushNotification\Si\Component\Application\Bridge\Mobile\IOs;

use PushNotification\Si\Component\Application\Entity\MobileClient\Exception\PushException;
use PushNotification\Si\Component\Application\Entity\MobileClient\MobileClientInterface;
use PushNotification\Si\Component\Application\Entity\MobileClient\MobileMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * APNs (Apple Push Notification services) implementation.
 */
class AppleMobileClient implements MobileClientInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $pushServerEndpoint;

    /**
     * @var string
     */
    protected $bundlePath;

    /**
     * @var string
     */
    protected $bundlePassphrase;

    /**
     * ApplePushNotificationClient constructor.
     *
     * @param LoggerInterface $logger
     * @param $pushServerEndpoint
     * @param $bundlePath
     * @param $bundlePassphrase
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        $pushServerEndpoint,
        $bundlePath,
        $bundlePassphrase,
        LoggerInterface $logger
    ) {
        // Check if $bundlePath file exists
        if (!is_readable($bundlePath)) {
            throw new FileNotFoundException(sprintf(
                '[%s] file does not exist.',
                $bundlePath
            ));
        }

        $this->pushServerEndpoint = $pushServerEndpoint;
        $this->bundlePath = $bundlePath;
        $this->bundlePassphrase = $bundlePassphrase;
        $this->logger = $logger;
    }

    /**
     * @see MobileClientInterface::push()
     */
    public function push(MobileMessage $mobileMessage)
    {
        // Structuring push message
        $payload = array(
            'aps' => array(
                'badge' => 1,
                'sound' => 'default',
                'alert' => array(
                    'loc-key' => $mobileMessage->getMessage(),
                ),
            ),
            'data' => $mobileMessage->getData(),
        );
        if ($args = $mobileMessage->getMessageArgs()) {
            $payload['aps']['alert']['loc-args'] = array();
            foreach ($args as $arg) {
                $payload['aps']['alert']['loc-args'][] = $arg;
            }
        }
        $payload = json_encode($payload);

        // Open a connection to the APNS server
        $this->logger->info('Connecting to Apple Push Notification server');
        $ctx = stream_context_create(array(
            'ssl' => array(
                'local_cert' => $this->bundlePath,
                'passphrase' => $this->bundlePassphrase,
            ),
        ));
        $stream = stream_socket_client(
            $this->pushServerEndpoint,
            $errno,
            $errstr,
            30,
            STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT,
            $ctx
        );

        // Connection failed?
        if (!$stream) {
            throw new PushException(
                'An error occured while trying to contact Apple Push Notification server.'
            );
        }

        // Build the binary notification
        $msg = sprintf('%s%s%s%s%s',
            chr(0),
            pack('n', 32),
            pack('H*', str_replace(' ', '', $mobileMessage->getDeviceToken())),
            pack('n', strlen($payload)),
            $payload
        );

        // Send it to the server
        $this->logger->info('Sending message to Apple Push Notification server', array(
            'deviceToken' => $mobileMessage->getDeviceToken(),
            'payload' => $payload,
        ));
        fwrite($stream, $msg, strlen($msg));

        // Close the connection to the server
        fclose($stream);
    }
}
