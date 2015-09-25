<?php

namespace LinkValue\MobileNotifBundle\Bridge\Mobile\Android;

use LinkValue\MobileNotifBundle\Entity\MobileClient\MobileClientInterface;
use LinkValue\MobileNotifBundle\Entity\MobileClient\MobileMessage;
use GuzzleHttp\Client as GuzzleClient;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;

/**
 * GCM (Google Cloud Messaging) implementation.
 *
 * @see https://developers.google.com/cloud-messaging/http
 */
class AndroidMobileClient implements MobileClientInterface
{
    /**
     * @var GuzzleClient
     */
    protected $guzzleClient;

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * @var array $pushServerURL
     */
    protected $pushServerURL;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * AndroidPushNotificationClient constructor.
     *
     * @param GuzzleClient          $guzzleClient
     * @param UrlGeneratorInterface $router
     * @param string                $pushServerRoute
     * @param LoggerInterface       $logger
     * @param string                $apiKey
     */
    public function __construct(
    GuzzleClient $guzzleClient, UrlGeneratorInterface $router, LoggerInterface $logger
    )
    {
        $this->guzzleClient = $guzzleClient;
        $this->router       = $router;
        $this->logger       = $logger;
    }

    /**
     * Set up the arguments from the configuration file
     *
     * @param array $params
     */
    public function setUp(array $params)
    {
        // Fetch pushServerURL
        $ctx                 = $this->getContext();
        $this->setContext(new RequestContext());
        $this->pushServerURL = $this->router->generate($params['pushServerUrl'], array(), true);
        $this->router->setContext($ctx);

        $this->apiKey = $params['apiKey'];
    }

    /**
     * @see MobileClientInterface::push()
     */
    public function push(MobileMessage $mobileMessage)
    {
        // @todo When we'll have an Android app to test with
    }
}
