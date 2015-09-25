<?php

namespace LinkValue\MobileNotifBundle\Event;

use LinkValue\MobileNotifBundle\Entity\Application;

/**
 * Application specific event class.
 */
class ApplicationEvent
{
    protected $application;

    /**
     * construct.
     *
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * return related.
     *
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }
}
