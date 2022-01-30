<?php

namespace Nours\TableBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
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
        $factory = $container->getDefinition('nours_table.factory');
        $tableTypesMap = $fieldTypesMap = array();

        // Search for table types
        $ids = $container->findTaggedServiceIds('nours_table.table_type');
        foreach ($ids as $id => $tags) {
            $tableTypesMap[$container->getDefinition($id)->getClass()] = new Reference($id);
        }

        $ids = $container->findTaggedServiceIds('nours_table.field_type');
        foreach ($ids as $id => $tags) {
            $fieldTypesMap[$container->getDefinition($id)->getClass()] = new Reference($id);
        }

        $factory->replaceArgument(0, ServiceLocatorTagPass::register($container, $tableTypesMap));
        $factory->replaceArgument(1, ServiceLocatorTagPass::register($container, $fieldTypesMap));
    }
}
