<?php

/*
 * This file is part of the JarvisBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LinkValue\JarvisBundle\Tests\Client;

use LinkValue\MobileNotif\Client\ClientInterface;
use LinkValue\JarvisBundle\Client\ClientCollection;

/**
 * Test ClientCollection class.
 *
 * @package JarvisBundle
 * @author Oliver Thebault <oliver.thebault@gmail.com>
 */
class ClientCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ClientCollection
     */
    private $clientCollection;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var ClientInterface
     */
    private $apnsClient;

    /**
     * @var ClientInterface
     */
    private $gcmClient;

    /**
     * Unit tests setUp.
     */
    protected function setUp()
    {
        $this->clientCollection = new ClientCollection();
        $this->client = $this->prophesize('LinkValue\MobileNotif\Client\ClientInterface')->reveal();
        $this->apnsClient = $this->prophesize('LinkValue\JarvisBundle\Client\ApnsClient')->reveal();
        $this->gcmClient = $this->prophesize('LinkValue\JarvisBundle\Client\GcmClient')->reveal();
    }

    /**
     * @test
     */
    public function testAddClientWithANonStringKey()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->clientCollection->addClient(5, $this->client);
    }

    /**
     * @test
     */
    public function testAddClientWithAlreadyRegisteredKey()
    {
        $this->setExpectedException('RuntimeException');

        $this->clientCollection
            ->addClient('something', $this->apnsClient)
            ->addClient('something', $this->gcmClient)
        ;
    }

    /**
     * @test
     */
    public function testAddClient()
    {
        $key = 'something';

        $this->clientCollection->addClient($key, $this->client);

        $this->assertEquals($this->client, $this->clientCollection->get($key));
    }

    /**
     * @test
     */
    public function testGetApnsClients()
    {
        $apnsClient1 = clone $this->apnsClient;
        $apnsClient2 = clone $this->apnsClient;

        $this->clientCollection
            ->addClient('apns-client-1', $apnsClient1)
            ->addClient('apns-client-2', $apnsClient2)
            ->addClient('gcm-client', $this->gcmClient)
            ->addClient('random-client', $this->client)
        ;

        $expectedCollection = (new ClientCollection())
            ->addClient('apns-client-1', $apnsClient1)
            ->addClient('apns-client-2', $apnsClient2)
        ;

        $this->assertEquals($expectedCollection, $this->clientCollection->getApnsClients());
    }

    /**
     * @test
     */
    public function testGetGcmClients()
    {
        $gcmClient1 = clone $this->gcmClient;
        $gcmClient2 = clone $this->gcmClient;

        $this->clientCollection
            ->addClient('gcm-client-1', $gcmClient1)
            ->addClient('gcm-client-2', $gcmClient2)
            ->addClient('apns-client', $this->apnsClient)
            ->addClient('random-client', $this->client)
        ;

        $expectedCollection = (new ClientCollection())
            ->addClient('gcm-client-1', $gcmClient1)
            ->addClient('gcm-client-2', $gcmClient2)
        ;

        $this->assertEquals($expectedCollection, $this->clientCollection->getGcmClients());
    }
}
