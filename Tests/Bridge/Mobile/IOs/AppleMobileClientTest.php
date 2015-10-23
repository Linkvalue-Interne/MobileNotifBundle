<?php

namespace LinkValue\MobileNotifBundle\Tests\Bridge\Mobile\IOs;


use PHPUnit_Framework_TestCase;
use LinkValue\MobileNotifBundle\Entity\MobileClient\MobileMessage;
use LinkValue\MobileNotifBundle\Bridge\Mobile\IOs\AppleMobileClient;

/**
 * Unit test class for AppleMobileClient.php.
 *
 * @see LinkValue\MobileNotifBundle\Bridge\Mobile\IOs\AppleMobileClient
 */
class AppleMobileClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * tests push() method throws an exception when an inexisting ssl bundle is given.
     */
    public function testFailToCreateClientBecauseOfInexistingBundleFile()
    {
        $this->setExpectedException('Symfony\Component\Filesystem\Exception\FileNotFoundException');

        new AppleMobileClient(
            'http://apple-push.com/endpoint',
            '/this/file/probably/doesnt/exists/bundle.pem',
            'random passphrase',
            $this->prophesize('Psr\Log\LoggerInterface')->reveal()
        );
    }

    /**
     * tests push() method throws an exception when an error occurs while trying to contact the push server.
     */
    public function testFailToConnectToPushServer()
    {
        $this->setExpectedException('LinkValue\MobileNotifBundle\Entity\MobileClient\Exception\PushException');

        $message = (new MobileMessage())
            ->setDeviceToken('123456789 123456789 123456789 123456789')
            ->setMessage('apple <3')
        ;

        $appleMobileClient = new AppleMobileClient(
            'http://random-apple-push.com/endpoint',
            __FILE__,
            'random passphrase',
            $this->prophesize('Psr\Log\LoggerInterface')->reveal()
        );

        @$appleMobileClient->push($message);
    }
}
