<?php

namespace LinkValue\JarvisBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from app/config files.
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
                        ->append($this->addApnsClientsNode())
                        ->append($this->addGcmClientsNode())
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * Add APNS clients node.
     *
     * @return \Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    private function addApnsClientsNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('apns');

        $node
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->arrayNode('services')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('logger')->defaultValue('logger')->end()
                            ->scalarNode('profiler')->defaultValue('link_value_mobile_notif.profiler.client_profiler')->end()
                        ->end()
                    ->end()
                    ->arrayNode('params')
                        ->children()
                            ->scalarNode('endpoint')->cannotBeEmpty()->defaultValue('tls://gateway.sandbox.push.apple.com:2195')->end()
                            ->scalarNode('ssl_pem_path')->cannotBeEmpty()->isRequired()->end()
                            ->scalarNode('ssl_passphrase')->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }

    /**
     * Add Google Cloud Messaging clients node.
     *
     * @return \Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    private function addGcmClientsNode()
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
                            ->scalarNode('logger')->defaultValue('logger')->end()
                            ->scalarNode('profiler')->defaultValue('link_value_mobile_notif.profiler.client_profiler')->end()
                        ->end()
                    ->end()
                    ->arrayNode('params')
                        ->children()
                            ->scalarNode('endpoint')->cannotBeEmpty()->defaultValue('https://android.googleapis.com/gcm/send')->end()
                            ->scalarNode('api_access_key')->cannotBeEmpty()->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }
}
