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
    public function buildView(View $view, TableInterface $table)
    {
        $options = $table->getOptions();

        $view->vars = array_merge($view->vars, array(
            'page' =>  $options['page'],
            'limit' => $options['limit'],
            'pages' => $options['pages'],
            'total' => $options['total'],
            'data' =>  $options['data'],
            'url' =>   $options['url'],
            'sortable' =>   $table->isSortable(),
            'searchable' => $table->isSearchable(),
        ));
    }
    /**
     * {@inheritdoc}
     */
    public function configureFieldOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'label'      => null,
            'sortable'   => false,
            'searchable' => false,
            'width'      => null,
            'property_path' => null
        ));

        $resolver->setAllowedTypes('sortable', 'bool');
        $resolver->setAllowedTypes('searchable', 'bool');
    }

    /**
     * {@inheritdoc}
     */
    public function buildFieldView(View $view, FieldInterface $field)
    {
        $options = $field->getOptions();

        $view->vars = array_merge($view->vars, array(
            'label' => $options['label'],
            'sortable' => $options['sortable'],
            'searchable' => $options['searchable'],
            'width' => $options['width'],
            'property_path' => $options['property_path'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'core';
    }
}