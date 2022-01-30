<?php

namespace Nours\TableBundle\Extension;

use Nours\TableBundle\Field\FieldInterface;
use Nours\TableBundle\Builder\TableBuilder;
use Nours\TableBundle\Table\TableInterface;
use Nours\TableBundle\Table\View;
use Symfony\Component\HttpFoundation\Request;
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
     * @param TableInterface $table
     * @param Request|null $request
     */
    public function handle(TableInterface $table, Request $request = null);

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
     * Normalize table options after collecting fields.
     *
     * @param array $options
     * @param FieldInterface[] $fields
     * @return array
     */
    public function normalizeTableOptions(array $options, array $fields): array;

    /**
     * Configures options for a field.
     *
     * @param OptionsResolver $resolver
     */
    public function configureFieldOptions(OptionsResolver $resolver);

    /**
     * If this extension has dependency over another extension, it should return its name.
     *
     * @return null|string|array
     */
    public function getDependency();

    /**
     * Returns the name of this extension.
     *
     * @return string
     */
    public function getName(): string;
}