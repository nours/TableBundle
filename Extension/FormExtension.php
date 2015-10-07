<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Extension;

use Nours\TableBundle\Field\FieldInterface;
use Nours\TableBundle\Table\TableInterface;
use Nours\TableBundle\Table\View;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * FormExtension
 *
 * Use form_type option on fields to provide a form type which be used for building a form for data filtering.
 *
 * The filter_data option can be used to provide default filter data, and it will be overwritten with form submission.
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class FormExtension extends AbstractExtension
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
            'form_type' => 'form',
            'form_options' => array(),
            'filter_data' => array(),
            'form' => null
        ));
        // Will be set upon request handling
        $resolver->setAllowedTypes('form', array('null'));
        $resolver->setAllowedTypes('form_options', array('array'));
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
            $builder = $this->formFactory->createNamedBuilder(
                $table->getOption('form_name'),
                $table->getOption('form_type'),
                null,
                array_replace($table->getOption('form_options'), array(
                    'method' => 'GET',
                    'csrf_protection' => false
                ))
            );

            $this->buildFilterForm($builder, $fields);
            $form = $builder->getForm();
            $table->setOption('form', $form);

            if ($request) {
                $form->handleRequest($request);

                if ($form->isValid()) {
                    $table->setOption('filter_data', $form->getData());
                }
            }
        }

    }

    /**
     * @param FormBuilder $builder
     * @param FieldInterface[] $fields
     */
    private function buildFilterForm(FormBuilder $builder, $fields)
    {
        foreach ($fields as $field) {
            $builder->add($field->getName(), $field->getOption('filter_type'), $field->getOption('filter_options'));
        }
    }

    /**
     * @param TableInterface $table
     * @return FieldInterface[]
     */
    private function getFilterFields(TableInterface $table)
    {
        $fields = array();

        foreach ($table->getFields() as $field) {
            if ($field->getOption('filterable') && $field->getOption('filter_type')) {
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
        return 'core';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'form';
    }
}