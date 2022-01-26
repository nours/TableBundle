<?php

namespace Nours\TableBundle\DependencyInjection\Compiler;

use Nours\TableBundle\Factory\Registry\TypeRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
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

//        if (class_exists(ServiceLocatorTagPass::class)) {
            $factory->replaceArgument(0, ServiceLocatorTagPass::register($container, $tableTypesMap));
            $factory->replaceArgument(1, ServiceLocatorTagPass::register($container, $fieldTypesMap));
//        } else {
//            // Symfony < 3.3 : service locator is not implemented yet
//            $locatorDefinition = new Definition(TypeRegistry::class);
//
//            $locatorDefinition->setArguments([$tableTypesMap]);
//            $id = 'nours_table.type_registry.table';
//            $container->setDefinition($id, $locatorDefinition);
//            $factory->replaceArgument(0, new Reference($id));
//
//            $locatorDefinition = clone $locatorDefinition;
//            $locatorDefinition->setArguments([$fieldTypesMap]);
//            $id = 'nours_table.type_registry.field';
//            $container->setDefinition($id, $locatorDefinition);
//            $factory->replaceArgument(1, new Reference($id));
//        }
    }
}
