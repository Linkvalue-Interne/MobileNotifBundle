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
     * @var array
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
        GuzzleClient $guzzleClient,
        UrlGeneratorInterface $router,
        $pushServerRoute,
        LoggerInterface $logger,
        $apiKey
    ) {
        $this->guzzleClient = $guzzleClient;
        $this->logger = $logger;
        $this->apiKey = $apiKey;

        // Fetch pushServerURL
        $ctx = $router->getContext();
        $router->setContext(new RequestContext());
        $this->pushServerURL = $router->generate($pushServerRoute, array(), true);
        $router->setContext($ctx);
    }

    /**
     * @see MobileClientInterface::push()
     */
    public function push(MobileMessage $mobileMessage)
    {
        // @todo When we'll have an Android app to test with
    }
}
