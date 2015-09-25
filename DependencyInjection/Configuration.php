<?php

namespace LinkValue\MobileNotifBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('link_value_mobile_notif');

        $rootNode
            ->children()
                ->arrayNode("clients")
                    ->isRequired()
                    ->children()
                        ->arrayNode("ios")
                            ->requiresAtLeastOneElement()
                            ->useAttributeAsKey("name")
                            ->prototype('array')
                                ->children()
                                    ->scalarNode("endpoint")->isRequired()->cannotBeEmpty()->end()
                                    ->scalarNode("ssl_pem")->isRequired()->cannotBeEmpty()->end()
                                    ->scalarNode("passphrase")->isRequired()->cannotBeEmpty()->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode("android")
                            ->requiresAtLeastOneElement()
                            ->useAttributeAsKey("name")
                            ->prototype('array')
                                ->children()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
