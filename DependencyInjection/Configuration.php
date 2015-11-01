<?php

namespace Nours\TableBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('nours_table');
        
        $rootNode
            ->children()
                ->arrayNode('extensions')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('orm')->defaultTrue()->end()
                        ->booleanNode('form')->defaultTrue()->end()
                        ->booleanNode('bootstrap_table')->defaultTrue()->end()
                        ->arrayNode('core')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('page')->defaultValue('page')->end()
                                ->scalarNode('limit')->defaultValue('limit')->end()
                                ->scalarNode('sort')->defaultValue('sort')->end()
                                ->scalarNode('order')->defaultValue('order')->end()
                                ->scalarNode('search')->defaultValue('search')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('themes')
                    ->addDefaultChildrenIfNoneSet()
                    ->prototype('scalar')->defaultValue('NoursTableBundle:Table:theme.html.twig')->end()
                ->end()
                ->scalarNode('form_theme')->defaultNull()->end()
                ->scalarNode('translation_domain')->defaultValue('messages')->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
