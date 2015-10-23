<?php

namespace LinkValue\MobileNotifBundle\Client;

use LinkValue\MobileNotifBundle\Model\Message;

/**
 * Interface to implement on a Mobile Client class.
 */
interface ClientInterface
{
    /**
     * Push a notification to a mobile client.
     *
     * @param MobileMessage $mobileMessage
     */
    public function push(Message $message);
}
