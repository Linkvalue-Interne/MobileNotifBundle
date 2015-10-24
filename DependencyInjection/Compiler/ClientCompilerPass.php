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
class ClientCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('linkvalue.mobilenotif.clients')) {
            return;
        }

        $clientCollection = $container->getDefinition('linkvalue.mobilenotif.clients');
        $tagged = $container->findTaggedServiceIds('link_value_mobile_notif.client');

        foreach ($tagged as $serviceId => $attributes) {
            foreach ($attributes as $attribute) {
                if (empty($attribute['key'])) {
                    throw new \InvalidArgumentException(sprintf('"link_value_mobile_notif.client" tag must define "key" key.'));
                }

                $clientCollection->addMethodCall('addClient', array(
                    $attribute['key'],
                    new Reference($serviceId),
                ));
            }
        }
    }
}
