<?php

namespace Nours\TableBundle\Table;

use Symfony\Component\OptionsResolver\OptionsResolver;
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
     * Configures default options for this table.
     * 
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver);
    
    /**
     * Builds the table.
     * 
     * @param TableBuilderInterface $builder
     */
    public function buildTable(TableBuilderInterface $builder);
}