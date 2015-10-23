<?php

namespace LinkValue\MobileNotifBundle\Client;

use InvalidArgumentException;
use Doctrine\Common\Collections\ArrayCollection;

class ClientCollection extends ArrayCollection
{
    /**
     * @var ClientCollection
     */
    protected $clients;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->clients = array();
    }

    /**
     * @param string $key key to store the client
     * @param ClientInterface $client
     *
     * @return ClientCollection
     *
     * @throws InvalidArgumentException if the key is not a String
     */
    public function addClient($key, ClientInterface $client)
    {
        if (!is_string($key))
            throw new InvalidArgumentException('The key must be a string.');

        if ($this->clients->has($key))
            throw new RuntimeException(sprintf('A client with name "%s" already exists.', $key));

        $this->clients->set($key, $client);

        return $this;
    }
}
