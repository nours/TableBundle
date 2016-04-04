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
        $registry = $container->getDefinition('nours_table.registry.dependency_injection');
        $tableServices = $fieldServices = array();
        
        // Search for table types
        $ids = $container->findTaggedServiceIds('nours_table.table_type');
        foreach ($ids as $id => $tags) {
            $alias = isset($tags[0]['alias']) ? $tags[0]['alias'] : null;

            if ($alias) {
                $tableServices[$alias] = $id;
            } else {
                trigger_error(sprintf(
                    "Declaring service %s with nours_table.table_type tag without alias is deprecated", $id
                ), E_USER_DEPRECATED);
                $registry->addMethodCall('setTableType', array(new Reference($id)));
            }
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
            $alias = isset($tags[0]['alias']) ? $tags[0]['alias'] : null;

            if ($alias) {
                $fieldServices[$alias] = $id;
            } else {
                trigger_error(sprintf(
                    "Declaring service %s with nours_table.field_type tag without alias is deprecated", $id
                ), E_USER_DEPRECATED);
                $registry->addMethodCall('setFieldType', array(new Reference($id)));
            }
        }

        $registry->replaceArgument(1, $tableServices);
        $registry->replaceArgument(2, $fieldServices);
    }
}
