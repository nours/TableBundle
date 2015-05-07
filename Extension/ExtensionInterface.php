<?php

namespace Nours\TableBundle\Extension;


use Nours\TableBundle\Table\Builder\TableBuilderInterface;
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
     * @param TableBuilderInterface $builder
     * @param array $options
     */
    public function buildTable(TableBuilderInterface $builder, array $options);

    /**
     * Configures default options for a table.
     *
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver);
}