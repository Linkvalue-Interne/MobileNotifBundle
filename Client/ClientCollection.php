<?php

/*
 * This file is part of the JarvisBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LinkValue\JarvisBundle\Client;

use Doctrine\Common\Collections\ArrayCollection;
use LinkValue\MobileNotif\Client\ClientInterface;

/**
 * ClientCollection.
 *
 * @package JarvisBundle
 * @author  Jamal Youssefi <jamal.youssefi@gmail.com>
 * @author  Valentin Coulon <valentin.c0610@gmail.com>
 */
class ClientCollection extends ArrayCollection
{
    /**
     * Add $client referenced by $key to the collection.
     *
     * @param string          $key    key to store the client
     * @param ClientInterface $client
     *
     * @return self
     *
     * @throws \InvalidArgumentException if the key is not a string
     * @throws \RuntimeException if the key already exists
     */
    public function addClient($key, ClientInterface $client)
    {
        if (!is_string($key)) {
            throw new \InvalidArgumentException('The client key must be a string.');
        }

        if ($this->containsKey($key)) {
            throw new \RuntimeException(sprintf('A client key "%s" already exists.', $key));
        }

        $this->set($key, $client);

        return $this;
    }

    /**
     * Retrieve all ApnsClient of a ClientCollection.
     *
     * @return ClientCollection
     */
    public function getApnsClients()
    {
        return $this->filter(function (ClientInterface $client) {
            return $client instanceof ApnsClient;
        });
    }

    /**
     * Retrieve all GcmClient of a ClientCollection.
     *
     * @return ClientCollection
     */
    public function getGcmClients()
    {
        return $this->filter(function (ClientInterface $client) {
            return $client instanceof GcmClient;
        });
    }
}
