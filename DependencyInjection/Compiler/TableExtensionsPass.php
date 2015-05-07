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
        
        // Search for extensions
        $priorityQueue = new \SplPriorityQueue();
        $ids = $container->findTaggedServiceIds('nours_table.extension');
        foreach ($ids as $id => $tags) {
            $priority = isset($tags[0]['priority']) ? $tags[0]['priority'] : 0;
            $priorityQueue->insert($id, $priority);
        }

        foreach ($priorityQueue as $id) {
            $factory->addMethodCall('addTableExtension', array(new Reference($id)));
        }
    }
}
