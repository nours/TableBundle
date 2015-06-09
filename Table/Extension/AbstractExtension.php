<?php

namespace Nours\TableBundle\Table\Extension;

use Nours\TableBundle\Field\FieldInterface;
use Nours\TableBundle\Table\Builder\TableBuilder;
use Nours\TableBundle\Table\TableInterface;
use Nours\TableBundle\Table\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractExtension
 *
 * @author David Coudrier <david.coudrier@gmail.com>
 */
abstract class AbstractExtension implements ExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function configureFieldOptions(OptionsResolver $resolver)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilder $builder, array $options)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function finishTable(TableBuilder $builder, array $options)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function handle(TableInterface $table, Request $request = null)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function buildView(View $view, TableInterface $table, array $options)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function buildFieldView(View $view, FieldInterface $field, array $options)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getDependency()
    {
        return 'core';
    }

    /**
     * Resolves option from fields
     *
     * @param TableInterface $table
     * @param $option
     * @param mixed $expected
     * @return mixed
     */
    protected function resolveFieldOption(TableInterface $table, $option, $expected = true)
    {
        if (($value = $table->getOption($option)) !== null) {
            // Value has been set in table option
            return $value;
        }

        foreach ($table->getFields() as $field) {
            if ($field->getOption($option) === $expected) {
                return $expected;
            }
        }

        return null;
    }
} 