<?php

/*
 * This file is part of the MobileNotifBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LinkValue\MobileNotifBundle\Profiler;

use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;

/**
 * ClientProfiler.
 *
 * @package MobileNotifBundle
 * @author  Jamal Youssefi <jamal.youssefi@gmail.com>
 * @author  Valentin Coulon <valentin.c0610@gmail.com>
 */
class ClientProfiler implements ClientProfilerInterface
{
    /**
     * @var array
     */
    private $calls;

    /**
     * @var int
     */
    private $counter;

    /**
     * @param Stopwatch $stopwatch
     */
    public function __construct(Stopwatch $stopwatch)
    {
        $this->calls = array();
        $this->counter = 0;
        $this->stopwatch = $stopwatch;
    }

    /**
     * @param $message
     *
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

        ++$this->counter;
    }

    /**
     * @return array
     */
    public function getCalls()
    {
        return $this->calls;
    }
}
