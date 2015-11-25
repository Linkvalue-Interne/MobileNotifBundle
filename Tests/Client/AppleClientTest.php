<?php

/*
 * This file is part of the MobileNotifBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LinkValue\MobileNotifBundle\Tests\Client;

use LinkValue\MobileNotifBundle\Client\AppleClient;

/**
 * Unit test class for AppleClient class.
 *
 * @author  Jamal Youssefi <jamal.youssefi@gmail.com>
 * @author  Valentin Coulon <valentin.c0610@gmail.com>
 */
class AppleClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * tests push() method throws an exception when an inexisting ssl bundle is given.
     */
    public function testFailToCreateClientBecauseOfInexistingBundleFile()
    {
        $this->setExpectedException('Symfony\Component\Filesystem\Exception\FileNotFoundException');

        (new AppleMobileClient($this->prophesize('Psr\Log\LoggerInterface')->reveal()))
            ->setUp([
                'endpoint' => 'http://apple-push.com/endpoint',
                'ssl_pem' => '/this/file/probably/doesnt/exists/bundle.pem',
                'passphrase' => 'random passphrase',
            ])
        ;
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

        $mobileClient = new AppleMobileClient($this->prophesize('Psr\Log\LoggerInterface')->reveal());
        $mobileClient->setUp([
                'endpoint' => 'http://random-apple-push.com/endpoint',
                'ssl_pem' => __FILE__,
                'passphrase' => 'random passphrase',
            ])
        ;
        $mobileClient->push($message);
    }
}
