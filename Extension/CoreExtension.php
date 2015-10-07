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
            'page'       => 1,      // The current page index (1 based)
            'limit'      => 10,     // The max item per pages
            'pages'      => null,   // The pages count
            'total'      => null,   // The total number items
            'data'       => null,   // The data (if specified at create time, otherwise should be loaded in handle)
            'pagination' => true,   // Is pagination activated
            'sort'       => null,   // The sort field
            'order'      => 'ASC'   // The sort order
        ));
        $resolver->setRequired('name');
    }

    /**
     * {@inheritdoc}
     */
    public function configureFieldOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'label' => null,
            'property_path' => function(Options $options) {
                return $options['name'];
            },
            'width'   => null,
            'display' => true,
        ));

        $resolver->setRequired('name');
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(View $view, TableInterface $table, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'name'       => $table->getName(),
            'page'       => $table->getPage(),
            'limit'      => $table->getLimit(),
            'pages'      => $table->getPages(),
            'total'      => $table->getTotal(),
//            'data'       => $table->getData(),
            'pagination' => $options['pagination'],
            'sort'       => $options['sort'],
            'order'      => $options['order'],
            'block_prefixes' => array('table_' . $table->getType()->getName(), 'table'),
            'cache_key' => 'table_' . $table->getType()->getName()
        ));
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