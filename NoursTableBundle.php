<?php

namespace Nours\TableBundle;

use Nours\TableBundle\DependencyInjection\Compiler\TableExtensionsPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Nours\TableBundle\DependencyInjection\Compiler\TableTypesPass;

class NoursTableBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new TableTypesPass());
        $container->addCompilerPass(new TableExtensionsPass());
    }
}
