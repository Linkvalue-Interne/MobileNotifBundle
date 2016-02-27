<?php

/*
 * This file is part of the MobileNotifBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LinkValue\MobileNotifBundle\Profiler;

/**
 * Trait which provides tools to profile a client implementation.
 *
 * @package MobileNotifBundle
 * @author Oliver Thebault <oliver.thebault@gmail.com>
 */
trait ClientProfilableTrait
{
    /**
     * @var ClientProfilerInterface $clientProfiler
     */
    protected $clientProfiler;

    /**
     * Constructor.
     *
     * @param ClientProfilerInterface $clientProfiler
     *
     * @return self
     */
    public function setClientProfiler(ClientProfilerInterface $clientProfiler)
    {
        $this->clientProfiler = $clientProfiler;

        return $this;
    }
}
