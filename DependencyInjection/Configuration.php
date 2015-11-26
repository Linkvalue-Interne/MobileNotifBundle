<?php

namespace LinkValue\MobileNotifBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
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
                ->arrayNode('clients')
                    ->isRequired()
                    ->children()
                        ->append($this->addAppleClientsNode())
                        ->append($this->addGcmClientsNode())
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * Get apple clients node.
     *
     * @return TreeBuilder
     */
    protected function addAppleClientsNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('apple');

        $node
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->arrayNode('services')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('logger')->isRequired()->defaultValue('logger')->end()
                            ->scalarNode('profiler')->isRequired()->defaultValue('linkvalue.mobilenotif.profiler.notif_profiler')->end()
                        ->end()
                    ->end()
                    ->arrayNode('params')
                        ->children()
                            ->scalarNode('endpoint')->defaultValue('tls://gateway.sandbox.push.apple.com:2195')->end()
                            ->scalarNode('ssl_pem_path')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('ssl_passphrase')->isRequired()->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }

    /**
     * Get Google Cloud Messaging clients node.
     *
     * @return TreeBuilder
     */
    protected function addGcmClientsNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('gcm');

        $node
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->arrayNode('services')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('logger')->isRequired()->defaultValue('logger')->end()
                            ->scalarNode('profiler')->isRequired()->defaultValue('linkvalue.mobilenotif.profiler.notif_profiler')->end()
                        ->end()
                    ->end()
                    ->arrayNode('params')
                        ->children()
                            ->scalarNode('endpoint')->defaultValue('https://android.googleapis.com/gcm/send')->end()
                            ->scalarNode('api_access_key')->isRequired()->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }
}
