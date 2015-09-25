<?php

namespace LinkValue\MobileNotifBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Definition;

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

        $clientNamespace = "LinkValue\MobileNotifBundle\Bridge\Mobile\Android";

        foreach ($config["clients"] as $type => $clients) {

            foreach ($clients as $name => $params) {

                $clientClass = $type == "ios" ? "AppleMobileClient" : "AndroidMobileClient";

                $client = new Definition($clientNamespace . "\\" . $clientClass, $params);

                $container->setDefinition($name, $client);
            }
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
