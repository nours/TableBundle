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
use Nours\TableBundle\Table\View;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Nours\TableBundle\Table\TableInterface;


/**
 * Class CoreExtension
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class CoreExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'page'    => 1,
            'limit'   => 10,
            'pages'   => null,
            'total'   => null,
            'data'    => null
        ));
        $resolver->setRequired('name');
    }

    /**
     * {@inheritdoc}
     */
    public function configureFieldOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'label' => function(Options $options) {
                return $options['name'];
            },
            'property_path' => function(Options $options) {
                return $options['name'];
            },
            'width'      => null,
            'display'  => true,
        ));

        $resolver->setRequired('name');
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(View $view, TableInterface $table, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'name'  => $table->getName(),
            'page'  => $table->getPage(),
            'limit' => $table->getLimit(),
            'pages' => $table->getPages(),
            'total' => $table->getTotal(),
            'data'  =>  $table->getData(),
            'block_prefixes' => array('table_' . $table->getType()->getName(), 'table'),
            'cache_key' => 'table_' . $table->getType()->getName()
        ));

        // Set the vars which will be exposed in serialization.
        $view->serializedVars = array('page', 'limit', 'pages', 'total', 'data');
    }

    /**
     * {@inheritdoc}
     */
    public function buildFieldView(View $view, FieldInterface $field, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'name' => $options['name'],
            'label' => $options['label'],
            'width' => $options['width'],
            'property_path' => $options['property_path'],
            'block_prefixes' => array('field_' . $field->getType()->getName(), 'field'),
            'cache_key' => 'field_' . $field->getType()->getName()
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getDependency()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'core';
    }
}