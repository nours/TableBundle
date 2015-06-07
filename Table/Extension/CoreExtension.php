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
use Doctrine\Common\Inflector\Inflector;
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
            'data'    => null,
            'url'     => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(View $view, TableInterface $table, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'page' =>  $options['page'],
            'limit' => $options['limit'],
            'pages' => $options['pages'],
            'total' => $options['total'],
            'data' =>  $options['data'],
            'url' =>   $options['url'],
            'sortable' =>   $table->isSortable(),
            'searchable' => $table->isSearchable(),
        ));

        // Block prefixes used for searching template blocks
        $view->options['block_prefixes'] = array('table_' . $table->getType()->getName(), 'table');
        $view->options['cache_key'] = 'table_' . $table->getType()->getName();
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
                return Inflector::tableize($options['name']);
            },
            'sortable'   => false,
            'searchable' => false,
            'width'      => null,
        ));

        $resolver->setRequired('name');
        $resolver->setAllowedTypes('sortable', 'bool');
        $resolver->setAllowedTypes('searchable', 'bool');
    }

    /**
     * {@inheritdoc}
     */
    public function buildFieldView(View $view, FieldInterface $field, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'name' => $options['name'],
            'label' => $options['label'],
            'sortable' => $options['sortable'],
            'searchable' => $options['searchable'],
            'width' => $options['width'],
            'property_path' => $options['property_path'],
        ));

        // Block prefixes used for searching template blocks
        $view->options['block_prefixes'] = array('field_' . $field->getType()->getName(), 'field');
        $view->options['cache_key'] = 'field_' . $field->getType()->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'core';
    }
}