<?php

/*
 * This file is part of the MobileNotifBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LinkValue\MobileNotifBundle\DataCollector;

use LinkValue\MobileNotifBundle\Profiler\NotifProfiler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * ClientCollection
 *
 * @package MobileNotifBundle
 * @author  Jamal Youssefi <jamal.youssefi@gmail.com>
 * @author  Valentin Coulon <valentin.c0610@gmail.com>
 */
class NotifDataCollector extends DataCollector
{
    private $profiler;

    /**
     * @param NotifProfiler $profiler
     */
    public function __construct(NotifProfiler $profiler)
    {
        $this->profiler = $profiler;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param \Exception|null $exception
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['notif'] = $this->profiler->getCalls();
    }

    /**
     * @return mixed
     */
    public function getCalls()
    {
        return $this->data['notif'];
    }

    /**
     * @return int
     */
    public function getNbrCall()
    {
        return count($this->data['notif']);
    }

    /**
     * @return int
     */
    public function getNbrErrors()
    {
        $errors = 0;
        foreach ($this->data['notif'] as $call) {
            if ($call['error'] == true) {
                $errors++;
            }
        }
        return $errors;
    }

    /**
     * @return int
     */
    public function getTotalTime()
    {
        $time = 0;
        foreach ($this->data['notif'] as $call) {
            $time += $call['duration'];
        }

        return $time;
    }

    public function getMemoryUsage()
    {
        $memory = 0;
        foreach ($this->data['notif'] as $call) {
            $memory += $call['memory_use'];
        }

        return $memory;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'notif';
    }
}
