<?php

namespace LinkValue\MobileNotifBundle\Client;

use Psr\Log\LoggerInterface;
use LinkValue\MobileNotifBundle\Exception\PushException;
use LinkValue\MobileNotifBundle\Model\Message;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * APNs (Apple Push Notification services) implementation.
 */
class AppleClient implements ClientInterface
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
     *
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Set up the arguments from the configuration file
     *
     * @param array $params
     *
     * @throws \FileNotFoundException
     */
    public function setUp(array $params)
    {
        // Check if $bundlePath file exists
        if (!is_readable($params['ssl_pem'])) {
            throw new FileNotFoundException(sprintf(
                    '[%s] file does not exist.', $params['ssl_pem']
            ));
        }
        $this->pushServerEndpoint = $params['endpoint'];
        $this->bundlePath         = $params['ssl_pem'];
        $this->bundlePassphrase   = $params['passphrase'];
    }

    /**
     * @see MobileClientInterface::push()
     */
    public function push(Message $message)
    {
        // Structuring push message
        $payload = array(
            'aps'  => array(
                'badge' => 1,
                'sound' => 'default',
                'alert' => array(
                    'loc-key' => $message->getMessage(),
                ),
            ),
            'data' => $message->getData(),
        );
        if ($args    = $message->getMessageArgs()) {
            $payload['aps']['alert']['loc-args'] = array();
            foreach ($args as $arg) {
                $payload['aps']['alert']['loc-args'][] = $arg;
            }
        }
        $payload = json_encode($payload);

        // Open a connection to the APNS server
        $this->logger->info('Connecting to Apple Push Notification server');
        $ctx    = stream_context_create(array(
            'ssl' => array(
                'local_cert' => $this->bundlePath,
                'passphrase' => $this->bundlePassphrase,
            ),
        ));
        $stream = stream_socket_client(
                $this->pushServerEndpoint, $errno, $errstr, 30, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx
        );

        // Connection failed?
        if (!$stream) {
            throw new PushException(
            'An error occured while trying to contact Apple Push Notification server.'
            );
        }

        // Build the binary notification
        $msg = sprintf('%s%s%s%s%s', chr(0), pack('n', 32), pack('H*', str_replace(' ', '', $message->getDeviceToken())), pack('n', strlen($payload)), $payload
        );

        // Send it to the server
        $this->logger->info('Sending message to Apple Push Notification server', array(
            'deviceToken' => $message->getDeviceToken(),
            'payload'     => $payload,
        ));
        fwrite($stream, $msg, strlen($msg));

        // Close the connection to the server
        fclose($stream);
    }
}
