<?php

namespace LinkValue\MobileNotifBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Collect all mobile clients and store it to application domain.
 */
class MobileClientCompilerPass implements CompilerPassInterface
{
    /**
     * @{inherit_doc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('link_value_mobile_notif.mobile_notif')) {
            return;
        }

        $definition = $container->getDefinition('link_value_mobile_notif.mobile_notif');
        $tagged = $container->findTaggedServiceIds('link_value_mobile_notif.mobile_client');

        foreach ($tagged as $serviceId => $attributes) {
            foreach ($attributes as $attribute) {
                if (empty($attribute['support'])) {
                    throw new \InvalidArgumentException(sprintf('"link_value_mobile_notif.mobile_client" tag must define "support" key.'));
                }

                $definition->addMethodCall('addMobileClient', array(
                    $attribute['support'],
                    new Reference($serviceId),
                ));
            }
        }
    }
}
