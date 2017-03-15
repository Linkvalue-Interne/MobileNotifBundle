<?php

/*
 * This file is part of the JarvisBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LinkValue\JarvisBundle\DataCollector;

use LinkValue\JarvisBundle\Profiler\ClientProfilerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * ClientDataCollector.
 *
 * @package JarvisBundle
 * @author  Jamal Youssefi <jamal.youssefi@gmail.com>
 * @author  Valentin Coulon <valentin.c0610@gmail.com>
 */
class ClientDataCollector extends DataCollector
{
    /**
     * @var ClientProfilerInterface
     */
    private $profiler;

    /**
     * @param ClientProfilerInterface $profiler
     */
    public function __construct(ClientProfilerInterface $profiler)
    {
        $this->profiler = $profiler;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'link_value_jarvis';
    }

    /**
     * @param Request         $request
     * @param Response        $response
     * @param \Exception|null $exception
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['push_calls'] = $this->profiler->getCalls();
    }

    /**
     * @return mixed
     */
    public function getCalls()
    {
        return $this->data['push_calls'];
    }

    /**
     * @return int
     */
    public function getCountCalls()
    {
        return count($this->data['push_calls']);
    }

    /**
     * @return int
     */
    public function getCountErrors()
    {
        $errors = 0;
        foreach ($this->data['push_calls'] as $call) {
            if ($call['error'] == true) {
                ++$errors;
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
        foreach ($this->data['push_calls'] as $call) {
            $time += $call['duration'];
        }

        return $time;
    }

    /**
     * @return int
     */
    public function getMemoryUsage()
    {
        $memory = 0;
        foreach ($this->data['push_calls'] as $call) {
            $memory += $call['memory_use'];
        }

        return $memory;
    }
}
