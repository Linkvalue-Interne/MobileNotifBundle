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
use LinkValue\MobileNotif\Model\Message;
use LinkValue\MobileNotifBundle\Client\AppleClient;
use LinkValue\MobileNotifBundle\Profiler\NotifProfiler;
use Psr\Log\LoggerInterface;
use Symfony\Component\Stopwatch\StopwatchEvent;

/**
 * Class AppleClientTest
 *
 * @author Raphael De Freitas <raphael@de-freitas.net>
 */
class AppleClientTest extends \PHPUnit_Framework_TestCase
{
    protected $client;

    /**
     * @return LoggerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getDummyLogger()
    {
        $dummyLogger = $this->getMock('Psr\Log\LoggerInterface');
        return $dummyLogger;
    }

    /**
     * @return NotifProfiler|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getDummyNotifProfiler()
    {
        $dummyStopwatch = $this->getMock('Symfony\Component\Stopwatch\Stopwatch');
        $dummyNotifProfiler = $this->getMock('LinkValue\MobileNotifBundle\Profiler\NotifProfiler', array(), array($dummyStopwatch));
        return $dummyNotifProfiler;
    }

    /**
     * @return Message|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getDummyMessage()
    {
        $dummyMessage = $this->getMock('LinkValue\MobileNotif\Model\Message');
        return $dummyMessage;
    }

    /**
     * @return StopwatchEvent|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getDummyStopwatchEvent()
    {
        $dummyStopwatchEvent = $this->getMock('Symfony\Component\Stopwatch\StopwatchEvent', array(), array('12345', 'category'));
        return $dummyStopwatchEvent;
    }

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->client = new AppleClient($this->getDummyLogger(), $this->getDummyNotifProfiler());
    }

    public function testProfilerStartAndStop()
    {
        $dummyStopwatchEvent = $this->getDummyStopwatchEvent();
        $dummyNotifProfiler = $this->getDummyNotifProfiler();
        $dummyNotifProfiler
            ->expects($this->once())
            ->method('startProfiling')
            ->will($this->returnValue($dummyStopwatchEvent));
        $dummyNotifProfiler
            ->expects($this->once())
            ->method('stopProfiling')
            ->will($this->returnValue(null));

        $client = new AppleClient($this->getDummyLogger(), $dummyNotifProfiler);

        $dummyMessage = $this->getDummyMessage();
        $dummyMessage
            ->expects($this->any())
            ->method('getContent')
            ->will($this->returnValue('MyMessage'));

        $client->push($dummyMessage);

    }
}
