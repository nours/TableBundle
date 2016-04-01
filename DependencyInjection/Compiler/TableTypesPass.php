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
        $registry = $container->getDefinition('nours_table.types_registry');
        
        // Search for table types
        $ids = $container->findTaggedServiceIds('nours_table.table_type');
        foreach ($ids as $id => $tags) {
            $registry->addMethodCall('addTableType', array(new Reference($id)));
        }
        
        // And for field types
        if ($ids = $container->findTaggedServiceIds('nours_table.table_field')) {
            throw new \DomainException(sprintf(
                "Tag nours_table.table_field are deprecated, please use nours_table.field_type instead (services %s)",
                implode(', ', array_keys($ids))
            ));
        }

        $ids = $container->findTaggedServiceIds('nours_table.field_type');
        foreach ($ids as $id => $tags) {
            $registry->addMethodCall('addFieldType', array(new Reference($id)));
        }
    }
}
