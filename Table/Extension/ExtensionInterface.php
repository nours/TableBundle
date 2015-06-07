<?php

namespace Nours\TableBundle\Table\Extension;

use Nours\TableBundle\Field\FieldInterface;
use Nours\TableBundle\Table\Builder\TableBuilder;
use Nours\TableBundle\Table\TableInterface;
use Nours\TableBundle\Table\View;
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
     * @param View $view
     * @param TableInterface $table
     * @param array $options
     */
    public function buildView(View $view, TableInterface $table, array $options);

    /**
     * @param View $view
     * @param FieldInterface $field
     * @param array $options
     */
    public function buildFieldView(View $view, FieldInterface $field, array $options);

    /**
     * Configures options for a table.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Configures options for a field.
     *
     * @param OptionsResolver $resolver
     */
    public function configureFieldOptions(OptionsResolver $resolver);

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