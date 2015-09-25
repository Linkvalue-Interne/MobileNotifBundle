<?php
namespace LinkValue\MobileNotifBundle;

use InvalidArgumentException;
use LinkValue\MobileNotifBundle\Entity\MobileClient\MobileClientInterface;
use LinkValue\MobileNotifBundle\Entity\MobileClient\MobileMessage;

class MobileNotif implements MobileClientInterface
{
    /**
     * @var MobileClientInterface[]
     */
    protected $mobileClients = [];

    /**
     * Current mobile client key to use to retrieve the mobile client
     * Set null if no mobile client set
     *
     * @var string
     */
    protected $currentMobileClientKey = null;

    /**
     * @param string $key key to store the client
     * @param MobileClientInterface $mobileClient
     *
     * @return MobileNotif
     *
     * @throws InvalidArgumentException if the key is not a String
     */
    public function addMobileClient($key, MobileClientInterface $mobileClient)
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException("Key must be a string");
        }
        //Set the currentMobileClientKey to the first enter by default
        if (is_null($this->currentMobileClientKey)) {
            $this->currentMobileClientKey = $key;
        }
        $this->mobileClients[$key] = $mobileClient;

        return $this;
    }

    /**
     *
     * @param string $key key of the mobileClient to use
     *
     * @return MobileNotif
     *
     * @throws InvalidArgumentException
     */
    public function using($key)
    {
        if (array_key_exists($key, $this->mobileClients)) {
            throw new InvalidArgumentException("Key not found");
        }
        $this->currentMobileClientKey = $key;

        return $this;
    }

    /**
     * @param MobileMessage $mobileMessage mesage to send
     *
     * @return MobileNotif
     */
    public function push(MobileMessage $mobileMessage)
    {
        $this->mobileClients[$this->currentMobileClientKey]->push($mobileMessage);

        return $this;
    }
}
