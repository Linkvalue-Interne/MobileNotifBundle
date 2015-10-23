<?php

namespace LinkValue\MobileNotifBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
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

        $clientNamespace = "LinkValue\MobileNotifBundle\Bridge\Mobile";

        foreach ($config["clients"] as $type => $clients) {

            foreach ($clients as $name => $data) {
                $services = isset($data['services']) ? $data['services'] : array();
                $params = isset($data['params']) ? $data['params'] : array();

                $clientClass = $type == "ios" ? "IOs\AppleMobileClient" : "Android\AndroidMobileClient";

                $client = new Definition($clientNamespace . "\\" . $clientClass);

                foreach ($services as $service_id) {
                    $client->addArgument(new Reference($service_id));
                }

                $client->addMethodCall('setUp', array($params));

                $container->setDefinition('link_value_mobile_notif.'.$name, $client);
            }
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
