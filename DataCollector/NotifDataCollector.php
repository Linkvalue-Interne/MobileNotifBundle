<?php
/**
 * Created by PhpStorm.
 * User: bersiroth
 * Date: 24/10/2015
 * Time: 16:36
 */
namespace LinkValue\MobileNotifBundle\DataCollector;

use LinkValue\MobileNotifBundle\Profiler\NotifProfiler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

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