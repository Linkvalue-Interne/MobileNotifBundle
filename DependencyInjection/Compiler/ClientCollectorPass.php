<?php

/*
 * This file is part of the MobileNotifBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LinkValue\MobileNotifBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Collect all mobile clients and store them into ClientCollection.
 *
 * @package MobileNotifBundle
 * @author  Jamal Youssefi <jamal.youssefi@gmail.com>
 * @author  Valentin Coulon <valentin.c0610@gmail.com>
 */
class ClientCollectorPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('link_value_mobile_notif.clients')) {
            return;
        }

        $clientCollectionService = $container->getDefinition('link_value_mobile_notif.clients');
        $taggedClientServices = $container->findTaggedServiceIds('link_value_mobile_notif.client');

        foreach ($taggedClientServices as $serviceId => $attributes) {
            foreach ($attributes as $attribute) {
                if (empty($attribute['name'])) {
                    throw new \InvalidArgumentException('"link_value_mobile_notif.client" tag must define "name" key.');
                }

                $clientCollectionService->addMethodCall('addClient', array(
                    $attribute['name'],
                    new Reference($serviceId),
                ));
            }
        }
    }
}
