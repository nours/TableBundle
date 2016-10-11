<?php

namespace Nours\TableBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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
                trigger_error(sprintf(
                    "Using alias for table type %s is deprecated, please remove them and use FQCNs", $id
                ), E_USER_DEPRECATED);

                $tableServices[$alias] = $id;
            }

            $tableServices[$container->getDefinition($id)->getClass()] = $id;
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
                trigger_error(sprintf(
                    "Using alias for field type %s is deprecated, please remove them and use FQCNs", $id
                ), E_USER_DEPRECATED);

                $fieldServices[$alias] = $id;
            }

            $fieldServices[$container->getDefinition($id)->getClass()] = $id;
        }

        $registry->replaceArgument(1, $tableServices);
        $registry->replaceArgument(2, $fieldServices);
    }
}
