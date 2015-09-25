<?php

namespace LinkValue\MobileNotifBundle\Entity;

/**
 * Application model class.
 */
class Application
{
    /**
     * @var int
     */
    protected $id;

    /**
     * ["apple", "google"].
     *
     * @var string
     */
    protected $support;

    /**
     * @var string
     */
    protected $name;

    /**
     * construct.
     */
    public function __construct()
    {
        $this->enabled = true;
    }

    /**
     * return Application id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * define Application id.
     *
     * @param int $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * return Application support.
     *
     * @return string
     */
    public function getSupport()
    {
        return $this->support;
    }

    /**
     * define Application support.
     *
     * @param string $support
     *
     * @return self
     */
    public function setSupport($support)
    {
        $this->support = $support;

        return $this;
    }

    /**
     * return Application name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * define Application name.
     *
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
