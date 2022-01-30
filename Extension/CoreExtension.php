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
use Nours\TableBundle\Util\Inflector;
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
            'url'        => null,   // Set to use ajax data loading
            'json_vars'  => array(),
            'serialized_vars' => array()
        ));
        $resolver->setRequired('name');

        $resolver->setNormalizer('page', function(Options $options, $value) {
            return filter_var($value, FILTER_VALIDATE_INT);
        });
        $resolver->setNormalizer('limit', function(Options $options, $value) {
            return filter_var($value, FILTER_VALIDATE_INT);
        });
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
            'block_prefixes' => array('table_' . $table->getType()->getBlockPrefix(), 'table'),
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
            'class_name' => Inflector::classify($field->getName())
        ));
    }

    private function buildBlockPrefixes(FieldInterface $field): array
    {
        // Field name first
        $prefixes = array('field_' . $field->getType()->getBlockPrefix());

        // Parent hierarchy
        foreach ($field->getAncestors() as $parent) {
            $prefixes[] = 'field_' . $parent->getBlockPrefix();
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
            $table->setPage($request->query->get($this->params['page'], $table->getOption('page')));
            $table->setLimit($request->query->get($this->params['limit'], $table->getOption('limit')));

            // Temporary (?) fix for InputBag::get returning array deprecation
            $sort = $request->query->all()['sort'] ?? $table->getOption('sort');

            $table->setOption('sort',   $sort);
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
    public function getName(): string
    {
        return 'core';
    }
}