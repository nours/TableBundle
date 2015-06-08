<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Table\Extension;

use Nours\TableBundle\Field\FieldInterface;
use Nours\TableBundle\Table\TableInterface;
use Nours\TableBundle\Table\View;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * FormFilterExtension
 *
 * Use form_type option on fields to provide a form type which be used for building a form for data filtering.
 *
 * If submitted on current request, this extension will provide the resulting form's data on the table's 'filter_params' option.
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class FormFilterExtension extends AbstractExtension
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'form_name' => 'filter',
            'filter_params' => null,      // Will be set upon request handling
            'form' => null      // Will be set upon request handling
        ));
        $resolver->setAllowedTypes('form', array('null'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureFieldOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'filterable' => function(Options $options) {
                return $options['filter_type'] !== null;
            },
            'filter_type' => null,
            'filter_options' => array()
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function handle(TableInterface $table, Request $request = null)
    {
        // Build the form if table has fields for filtering
        if ($fields = $this->getFilterFields($table)) {
            $form = $this->createFilterForm($fields, $table->getOption('form_name'));
            $table->setOption('form', $form);

            if ($request) {
                $form->handleRequest($request);

                if ($form->isValid()) {
                    $table->setOption('filter_params', $form->getData());
                }
            }
        }

    }

    /**
     * @param FieldInterface[] $fields
     * @param $name
     * @return \Symfony\Component\Form\Form
     */
    private function createFilterForm($fields, $name)
    {
        $builder = $this->formFactory->createNamedBuilder($name, 'form', null, array(
            'method' => 'GET',
            'csrf_protection' => false
        ));

        foreach ($fields as $field) {
            $builder->add($field->getName(), $field->getOption('filter_type'), $field->getOption('filter_options'));
        }

        return $builder->getForm();
    }

    /**
     * @param TableInterface $table
     * @return FieldInterface[]
     */
    private function getFilterFields(TableInterface $table)
    {
        $fields = array();

        foreach ($table->getFields() as $field) {
            if ($field->getOption('filterable')) {
                $fields[] = $field;
            }
        }

        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(View $view, TableInterface $table, array $options)
    {
        /** @var FormInterface $form */
        if ($form = $options['form']) {
            $view->vars['form'] = $form->createView();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDependency()
    {
        return 'orm';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'form';
    }
}