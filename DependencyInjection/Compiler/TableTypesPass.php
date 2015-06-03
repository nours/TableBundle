<?php

namespace Nours\TableBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Register table and table field types into the table factory.
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TableTypesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('nours_table.factory')) {
            return;
        }
        
        $factory = $container->getDefinition('nours_table.factory');
        
        // Search for table types
        $ids = $container->findTaggedServiceIds('nours_table.table_type');
        foreach ($ids as $id => $tags) {
            $factory->addMethodCall('addTableType', array(new Reference($id)));
        }
        
        // And for field types
        $ids = $container->findTaggedServiceIds('nours_table.table_field');
        foreach ($ids as $id => $tags) {
            $factory->addMethodCall('addFieldType', array(new Reference($id)));
        }
    }
}
