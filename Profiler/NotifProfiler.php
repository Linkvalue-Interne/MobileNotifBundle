<?php
/**
 * Created by PhpStorm.
 * User: bersiroth
 * Date: 24/10/2015
 * Time: 17:00
 */

namespace LinkValue\MobileNotifBundle\Profiler;

use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;

class NotifProfiler
{
    /**
     * @var array
     */
    private $calls = array();

    /**
     * @var int
     */
    private $counter = 0;

    /**
     * @param Stopwatch $stopwatch
     */
    public function __construct(Stopwatch $stopwatch)
    {
        $this->stopwatch = $stopwatch;
    }

    /**
     * @param $message
     * @return StopwatchEvent
     */
    public function startProfiling($message)
    {
        $this->calls[$this->counter] = array(
            'message' => $message,
            'duration' => null,
            'memory_start' => memory_get_usage(true),
            'memory_end' => null,
            'memory_use' => null,
            'error' => null,
            'error_message' => null,
        );

        return $this->stopwatch->start($message);
    }

    /**
     * @param StopwatchEvent $event
     * @param $result
     */
    public function stopProfiling(StopwatchEvent $event, $result)
    {
        $event->stop();

        $memory = memory_get_usage(true);
        $values = array(
            'duration' => $event->getDuration(),
            'memory_end' => $memory,
            'memory_use' => $memory - $this->calls[$this->counter]['memory_start'],
            'error' => $result['error'],
            'error_message' => $result['error_message'],
        );

        $this->calls[$this->counter] = array_merge($this->calls[$this->counter], $values);

        $this->counter++;
    }

    /**
     * @return array
     */
    public function getCalls()
    {
        return $this->calls;
    }

    /**
     * @return int
     */
    public function getNbCall()
    {
        return $this->counter;
    }
}