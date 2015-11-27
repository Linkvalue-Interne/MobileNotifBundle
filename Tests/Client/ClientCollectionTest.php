<?php

/*
 * This file is part of the MobileNotifBundle package.
 *
 * (c) Raphael De Freitas <raphael@de-freitas.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LinkValue\MobileNotifBundle\Tests\Client\ClientCollection;

use LinkValue\MobileNotif\Client\ClientInterface;
use LinkValue\MobileNotif\Model\Message;
use LinkValue\MobileNotifBundle\Client\ClientCollection;

/**
 * Class ClientCollectionTest
 *
 * @author Raphael De Freitas <raphael@de-freitas.net>
 */
class ClientCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testAddClient()
    {
        $clientCollection = new ClientCollection();
        /** @var ClientInterface $testClient */
        $testClient = $this->getMock('LinkValue\MobileNotif\Client\ClientInterface');
        $clientCollection->addClient('test', $testClient);
        $this->assertEquals($testClient, $clientCollection->get('test'));
    }

    public function testAddClientWithNonStringKey()
    {
        $clientCollection = new ClientCollection();
        $this->setExpectedException('\InvalidArgumentException');
        /** @var ClientInterface $testClient */
        $testClient = $this->getMock('LinkValue\MobileNotif\Client\ClientInterface');
        $clientCollection->addClient(42, $testClient);
    }

    public function testAddClientTwice()
    {
        $clientCollection = new ClientCollection();
        /** @var ClientInterface $testClient */
        $testClient = $this->getMock('LinkValue\MobileNotif\Client\ClientInterface');
        $clientCollection->addClient('test', $testClient);
        $this->setExpectedException('\RuntimeException');
        $clientCollection->addClient('test', $testClient);
    }
}