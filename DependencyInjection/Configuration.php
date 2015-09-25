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
                    ->children()
                        ->arrayNode("ios")
                            ->useAttributeAsKey("name")
                            ->prototype('array')
                                ->children()
                                    ->scalarNode("target")->isRequired()->end()
                                    ->scalarNode("ssl_pem")->isRequired()->end()
                                    ->scalarNode("passphrase")->isRequired()->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode("android")
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
