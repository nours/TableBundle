<?php

namespace Nours\TableBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class NoursTableExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
//        $loader->load('fields.yml');

        $container->setParameter('nours_table.themes', $config['themes']);
        $container->setParameter('nours_table.form_theme', $config['form_theme']);
        $container->setParameter('nours_table.extension.core', $config['extensions']['core']);

        if ($config['extensions']['orm']) {
            $container
                ->getDefinition('nours_table.extension.orm')
                ->addTag('nours_table.extension');
        }

        if ($config['extensions']['form']) {
            $container
                ->getDefinition('nours_table.extension.form')
                ->addTag('nours_table.extension')
            ;
        }

        if ($config['extensions']['bootstrap_table']) {
            $container
                ->getDefinition('nours_table.extension.bootstrap_table')
                ->addTag('nours_table.extension')
            ;
        }
    }
}
