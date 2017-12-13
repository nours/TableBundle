<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class TwigExtensionPass
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TwigExtensionPass implements CompilerPassInterface
{
    /**
     * Adds runtime loader for symfony 2.8 (without twig.runtime tag support)
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('twig.runtime_loader')) {
            $container
                ->getDefinition('twig')
                ->addMethodCall('addRuntimeLoader', array(new Reference('nours_table.twig.runtime_loader')))
            ;
        }
    }
}