<?php

/*
 * This file is part of the MobileNotifBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LinkValue\MobileNotifBundle\Client;

use Doctrine\Common\Collections\ArrayCollection;
use LinkValue\MobileNotif\Client\ClientInterface;

/**
 * ClientCollection.
 *
 * @author  Jamal Youssefi <jamal.youssefi@gmail.com>
 * @author  Valentin Coulon <valentin.c0610@gmail.com>
 */
class ClientCollection extends ArrayCollection
{
    /**
     * @param string          $key    key to store the client
     * @param ClientInterface $client
     *
     * @return ClientCollection
     *
     * @throws \InvalidArgumentException if the key is not a String
     */
    public function addClient($key, ClientInterface $client)
    {
        if (!is_string($key)) {
            throw new \InvalidArgumentException('The key must be a string.');
        }

        if ($this->containsKey($key)) {
            throw new \RuntimeException(sprintf('A client with name "%s" already exists.', $key));
        }

        $this->set($key, $client);

        return $this;
    }
}
