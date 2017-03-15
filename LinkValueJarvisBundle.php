<?php

namespace LinkValue\JarvisBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use LinkValue\JarvisBundle\DependencyInjection\Compiler\ClientCollectorPass;

class LinkValueJarvisBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ClientCollectorPass());
    }
}
