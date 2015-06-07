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
use Nours\TableBundle\Table\Builder\TableBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Nours\TableBundle\Table\Extension\ExtensionInterface;


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
    public function getName()
    {
        return $this->type->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        return $this->type->setDefaultOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilder $builder, array $options)
    {
        return $this->type->buildTable($builder, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(View $view, TableInterface $table, array $options)
    {
        return $this->type->buildView($view, $table, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function buildFieldView(View $view, FieldInterface $field, array $options)
    {
        return $this->type->buildFieldView($view, $field, $options);
    }

    /**
     * @param TableInterface $table
     * @return View
     */
    public function createView(TableInterface $table)
    {
        $tableType = $table->getType();
        $view = new View();

        // Create fields views
        foreach ($table->getFields() as $field) {
            $type = $field->getType();
            $options = $field->getOptions();

            $fieldView = new View($view);

            $type->buildView($fieldView, $field, $options);
            $tableType->buildFieldView($fieldView, $field, $options);

            foreach ($this->extensions as $extension) {
                $extension->buildFieldView($fieldView, $field, $options);
            }

            $view->fields[$field->getName()] = $fieldView;
        }

        $this->buildView($view, $table, $table->getOptions());

        foreach ($this->extensions as $extension) {
            $extension->buildView($view, $table, $table->getOptions());
        }

        return $view;
    }
}