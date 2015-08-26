<?php

namespace Nours\TableBundle\Table;

use Nours\TableBundle\Field\FieldInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Nours\TableBundle\Builder\TableBuilder;

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
     * The name of the extension to load.
     *
     * @return string|null
     */
    public function getExtension();

    /**
     * Configures default options for this table.
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver);
    
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
     * @param TableInterface $table
     * @param array $options
     */
    public function buildView(View $view, TableInterface $table, array $options);

    /**
     * Builds a field view.
     *
     * @param View $view
     * @param FieldInterface $field
     * @param array $options
     */
    public function buildFieldView(View $view, FieldInterface $field, array $options);
}