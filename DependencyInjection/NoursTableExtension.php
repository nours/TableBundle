<?php

namespace Nours\TableBundle\DependencyInjection;

use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\Serializer;
use LogicException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

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

        $container->setParameter('nours_table.themes', $config['themes']);
        $container->setParameter('nours_table.extension.core', $config['extensions']['core']);

        if ($config['extensions']['orm']) {
            if (!interface_exists(EntityManagerInterface::class)) {
                throw new LogicException('Doctrine ORM must be installed to enable table ORM extension');
            }

            $loader->load('orm.yml');
        }

        if ($config['extensions']['form']) {
            if (!class_exists(Form::class)) {
                throw new LogicException('symfony/form must be installed to enable table form extension');
            }

            $container->setParameter('nours_table.form_theme', $config['form_theme']);

            $loader->load('form.yml');
        }

        if ($config['extensions']['bootstrap_table']) {
            $loader->load('bootstrap_table.yml');
        }

        // JMS Serializer handler
        if (class_exists(Serializer::class)) {
            $loader->load('jms_serializer.yml');
        }
    }
}
