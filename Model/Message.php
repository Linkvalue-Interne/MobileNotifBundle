<?php

namespace LinkValue\MobileNotifBundle\Model;

class Message
{
    /**
     * @var string
     */
    protected $deviceToken;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var array
     */
    protected $messageArgs;

    /**
     * @var array
     */
    protected $data;

    /**
     * construct.
     */
    public function __construct()
    {
        $this->messageArgs = array();
    }

    /**
     * @return string
     */
    public function getDeviceToken()
    {
        return $this->deviceToken;
    }

    /**
     * @param string $deviceToken
     *
     * @return self
     */
    public function setDeviceToken($deviceToken)
    {
        $this->deviceToken = $deviceToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return self
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return array
     */
    public function getMessageArgs()
    {
        return $this->messageArgs;
    }

    /**
     * @param array $messageArgs
     *
     * @return self
     */
    public function setMessageArgs(array $messageArgs)
    {
        $this->messageArgs = $messageArgs;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return self
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}
