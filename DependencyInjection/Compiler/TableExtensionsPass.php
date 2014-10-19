<?php

namespace Nours\TableBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Register table extensions into the table factory.
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TableExtensionsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('nours_table.table_factory')) {
            return;
        }
        
        $factory = $container->getDefinition('nours_table.table_factory');
        
        // Search for table types
        $ids = $container->findTaggedServiceIds('nours_table.table_extension');
        foreach ($ids as $id => $tags) {
            $factory->addMethodCall('addTableExtension', array(new Reference($id)));
        }
    }
}
