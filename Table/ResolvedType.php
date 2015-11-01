<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Table;
use Nours\TableBundle\Field\FieldInterface;
use Nours\TableBundle\Builder\TableBuilder;
use Nours\TableBundle\Field\FieldTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Nours\TableBundle\Extension\ExtensionInterface;


/**
 * Class ResolvedType
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class ResolvedType implements TableTypeInterface
{
    private $type;

    /**
     * @var ExtensionInterface[]
     */
    private $extensions;

    public function __construct(TableTypeInterface $type, array $extensions)
    {
        $this->type = $type;
        $this->extensions = $extensions;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension()
    {
        return $this->type->getExtension();
    }

    /**
     * @return ExtensionInterface[]
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->type->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $this->type->configureOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilder $builder, array $options)
    {
        $this->type->buildTable($builder, $options);
    }

    /**
     * Handle a request for a table
     *
     * @param TableInterface $table
     * @param Request $request
     */
    public function handle(TableInterface $table, Request $request = null)
    {
        foreach ($this->extensions as $extension) {
            /** @var ExtensionInterface $extension */
            $extension->handle($table, $request);
        }

        // After all extensions have passed, the type may have specific handling
        $this->type->handle($table, $request);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(View $view, TableInterface $table, array $options)
    {
        $tableType = $table->getType();

        // Fields views are created using :
        // - the field type
        // - the table type
        // - table extensions
        foreach ($table->getFields() as $field) {
            if ($field->isDisplayed()) {
                $type = $field->getType();
                $fieldOptions = $field->getOptions();

                $fieldView = new View($view);

                foreach (array_reverse($field->getAncestors()) as $parent) {
                    /** @var FieldTypeInterface $parent */
                    $parent->buildView($fieldView, $field, $fieldOptions);
                }
                $type->buildView($fieldView, $field, $fieldOptions);
                $tableType->buildFieldView($fieldView, $field, $fieldOptions);

                foreach ($this->extensions as $extension) {
                    $extension->buildFieldView($fieldView, $field, $fieldOptions);
                }

                $view->fields[$field->getName()] = $fieldView;
            }
        }

        // Table views are created using :
        // - the underlying table type
        // - table extensions
        $this->type->buildView($view, $table, $options);

        foreach ($this->extensions as $extension) {
            $extension->buildView($view, $table, $options);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildFieldView(View $view, FieldInterface $field, array $options)
    {
        $this->type->buildFieldView($view, $field, $options);
    }

    /**
     * @param TableInterface $table
     * @return View
     */
    public function createView(TableInterface $table)
    {
        $view = new View();
        $view->table = $table;

        $this->buildView($view, $table, $table->getOptions());

        return $view;
    }
}