<?php

namespace LinkValue\MobileNotifBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use LinkValue\MobileNotifBundle\DependencyInjection\Compiler\ClientCompilerPass;

class LinkValueMobileNotifBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ClientCompilerPass());
    }
}
