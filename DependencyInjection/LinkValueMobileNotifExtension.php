<?php

namespace LinkValue\MobileNotifBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This is the class that loads and manages LinkValueMobileNotifBundle configuration.
 */
class LinkValueMobileNotifExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->registerClients($config, $container);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * Register each client as service, such as "link_value_mobile_notif.clients.the_client_type.my_custom_client_name"
     *
     * @param array $config
     * @param ContainerBuilder $container
     */
    private function registerClients(array $config, ContainerBuilder $container)
    {
        foreach ($config['clients'] as $clientType => $clients) {
            $clientFQCN = ($clientType == 'apns') ? 'LinkValue\MobileNotifBundle\Client\ApnsClient' : 'LinkValue\MobileNotifBundle\Client\GcmClient';

            foreach ($clients as $clientName => $clientConfig) {
                $params = isset($clientConfig['params']) ? $clientConfig['params'] : array();
                $services = isset($clientConfig['services']) ? $clientConfig['services'] : array();

                // The final client name is a concatenation of the client type (apns/gcm) and the client name (defined by user) separated by a point '.'
                $clientName = sprintf('%s.%s', $clientType, $clientName);

                // Register client with required stuff
                $client = $container->register(sprintf('link_value_mobile_notif.clients.%s', $clientName), $clientFQCN)
                    ->addMethodCall('setUp', array($params))
                    ->addTag('link_value_mobile_notif.client', array('name' => $clientName))
                ;

                // Set optional logger
                if(!empty($services['logger'])) {
                    $client->addMethodCall('setLogger', array(new Reference($services['logger'])));
                }

                // Set optional profiler
                if(!empty($services['profiler'])) {
                    $client->addMethodCall('setClientProfiler', array(new Reference($services['profiler'])));
                }
            }
        }
    }
}
