<?php

namespace Nours\TableBundle\Table;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Nours\TableBundle\Table\Builder\TableBuilder;

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
     * @param TableBuilder $builder
     * @param array $options
     */
    public function buildTable(TableBuilder $builder, array $options);

    /**
     * Builds a table view.
     *
     * @param View $view
     * @param array $options
     */
    public function buildView(View $view, array $options);

    /**
     * Builds a field view.
     *
     * @param View $view
     * @param array $options
     */
    public function buildFieldView(View $view, array $options);
}