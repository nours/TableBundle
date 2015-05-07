<?php

namespace Nours\TableBundle\Table;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Nours\TableBundle\Factory\TableFactoryInterface;
use Nours\TableBundle\Table\Builder\TableBuilderInterface;

/**
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
interface TableTypeInterface
{
    /**
     * The name of the table type, should be unique in application.
     *
     * @return string
     */
    public function getName();

    /**
     * Creates a builder ready to construct this table.
     * 
     * @param string $name
     * @param TableFactoryInterface $factory
     * @param array $options
     * @return TableBuilderInterface
     */
    public function createBuilder($name, TableFactoryInterface $factory, array $options = array());
    
    /**
     * Configures default options for this table.
     * 
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver);
    
    /**
     * Builds the table.
     * 
     * @param TableBuilderInterface $builder
     * @param array $options
     */
    public function buildTable(TableBuilderInterface $builder, array $options);
}