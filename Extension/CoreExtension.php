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
use Doctrine\Common\Inflector\Inflector;
use Nours\TableBundle\Field\FieldInterface;
use Nours\TableBundle\Table\View;
use Symfony\Component\HttpFoundation\Request;
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
    private $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'page'       => 1,      // The current page index (1 based)
            'limit'      => 10,     // The max item per pages
            'pages'      => null,   // The pages count
            'total'      => function(Options $options) {   // The total number items
                if ($data = $options['data']) {
                    return count($data);
                }
                return null;
            },
            'data'       => null,   // The data (if specified at create time, otherwise should be loaded in handle)
            'pagination' => true,   // Is pagination activated
            'sort'       => null,   // The sort field
            'order'      => 'ASC',  // The sort order
            'url'        => null    // Set to use ajax data loading
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
            'url'        => $options['url'],
            'block_prefixes' => array('table_' . $table->getType()->getName(), 'table'),
            'cache_key'  => 'table_' . $table->getType()->getName()
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
            'block_prefixes' => $this->buildBlockPrefixes($field),
            'cache_key' => 'field_' . $field->getType()->getName(),
            'class_name' => Inflector::classify($field->getName())
        ));
    }

    private function buildBlockPrefixes(FieldInterface $field)
    {
        // Field name first
        $prefixes = array('field_' . $field->getType()->getName());

        // Parent hierarchy
        foreach ($field->getAncestors() as $parent) {
            $prefixes[] = 'field_' . $parent->getName();
        }

        $prefixes[] = 'field';
        return $prefixes;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(TableInterface $table, Request $request = null)
    {
        if ($request) {
            // Override ORM parameters from request
            $table->setOption('page',   $request->query->get($this->params['page'], $table->getOption('page')));
            $table->setOption('limit',  $request->query->get($this->params['limit'], $table->getOption('limit')));
            $table->setOption('sort',   $request->query->get($this->params['sort'], $table->getOption('sort')));
            $table->setOption('order',  $request->query->get($this->params['order'], $table->getOption('order')));
            $table->setOption('search', $request->query->get($this->params['search'], $table->getOption('search')));
        }
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