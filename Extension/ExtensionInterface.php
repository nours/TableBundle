<?php

namespace Nours\TableBundle\Extension;

use Nours\TableBundle\Table\Builder\TableBuilder;
use Nours\TableBundle\Table\TableInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface ExtensionInterface
 *
 * @author David Coudrier <david.coudrier@gmail.com>
 */
interface ExtensionInterface
{
    /**
     * Builds the table.
     *
     * @param TableBuilder $builder
     * @param array $options
     */
    public function buildTable(TableBuilder $builder, array $options);

    /**
     * @param TableBuilder $builder
     * @param array $options
     */
    public function finishTable(TableBuilder $builder, array $options);

    /**
     * Configures default options for a table.
     *
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver);

    /**
     * @param TableInterface $table
     * @param array $options
     */
    public function loadTable(TableInterface $table, array $options);

    /**
     * If this extension has dependency over another extension, it should return it's name.
     *
     * @return string
     */
    public function getDependency();

    /**
     * Returns the name of this extension.
     *
     * @return string
     */
    public function getName();
}