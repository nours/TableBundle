<?php

namespace Nours\TableBundle\Table;

use Nours\TableBundle\Field\FieldInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Nours\TableBundle\Builder\TableBuilder;

/**
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
interface TableTypeInterface
{
    /**
     * The name of the block prefix for rendering.
     *
     * Defaults to snake cased of type class name.
     *
     * @return string
     */
    public function getBlockPrefix(): string;

    /**
     * The name of the extension to load.
     *
     * @return string|null
     */
    public function getExtension(): ?string;

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

    /**
     * Handle a request for a table
     *
     * @param TableInterface $table
     * @param Request|null $request
     */
    public function handle(TableInterface $table, Request $request = null);
}