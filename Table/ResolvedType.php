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
use Symfony\Component\HttpFoundation\Request;
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
     * {@inheritdoc}
     */
    public function handle(TableInterface $table, Request $request = null)
    {
//        $this->type->handle($request, $table, $options);

        // Loop over extensions (in reverse order), and stops when one did populate data
        // Configuration can propage from most dependant extensions to least
        // They will be able to share config using table options
        $extensions = $this->extensions;
        while (($extension = array_pop($extensions)) && (!$table->getData())) {
            /** @var ExtensionInterface $extension */
            $extension->handle($table, $request);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(View $view, TableInterface $table, array $options)
    {
        $tableType = $table->getType();

        // Create fields views using :
        // - the field type
        // - the table type
        // - table extensions
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

        // Build the table view using :
        // - the underlying table type
        // - table extensions
        $this->type->buildView($view, $table, $options);

        foreach ($this->extensions as $extension) {
            $extension->buildView($view, $table, $table->getOptions());
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

        $this->buildView($view, $table, $table->getOptions());

        return $view;
    }
}