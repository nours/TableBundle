<?php

namespace Nours\TableBundle\Extension;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Nours\TableBundle\Field\Field;
use Nours\TableBundle\Field\FieldInterface;
use Nours\TableBundle\Table\TableInterface;
use Nours\TableBundle\Table\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Builds a pager upon query.
 *
 * Implements search filter, and sorting.
 *
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class DoctrineORMExtension extends AbstractExtension
{
    /**
     * @var EntityManager
     */
    private $entityManager;


    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        // Query builder default if class option is provided
        $defaultQueryBuilder = function(Options $options) {
            $class = $options['class'];

            if (!empty($class)) {
                return $this->entityManager->getRepository($class)->createQueryBuilder('_root');
            }
            return null;
        };

        $resolver->setDefaults(array(
            'class' => null,
            'query_builder' => $defaultQueryBuilder,
            'search' => null,
            'query_builder_filter' => null,
            'fetch_join_collection' => true
        ));
        $resolver->setAllowedTypes('query_builder', array('Doctrine\ORM\QueryBuilder', 'null'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureFieldOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'sortable'   => false,
            'searchable' => false,
            'association' => null,  // Association for field mapping
            'property' => null,     // The property in the association
            'property_path' => function(Options $options) {
                $association = $options['association'];
                $property    = $options['property'];
                if ($association) {
                    // The property path now defaults to association.property
                    if (!$property) {
                        throw new \DomainException(sprintf(
                            "property must be set for field %s, or provide a custom property_path",
                            $options['name']
                        ));
                    }
                    return $association . '.' . $property;
                }
                return $options['name'];
            },

            /**
             * Alias of the association, defaults to the association name if any, otherwise '_root'.
             *
             * ex : author, _root
             */
            'alias' => function(Options $options) {
                if ($association = $options['association']) {
                    return $association;
                }
                return '_root';
            },

            /**
             * The parent association alias, defaults to _root.
             */
            'parent_alias' => '_root',

            /**
             * Path to current object in query, based on parent alias.
             *
             * Will be used for making joins and by filtering.
             *
             * ex : _root.author
             */
            'association_path' => function(Options $options) {
                $parentAlias = $options['parent_alias'];

                return $parentAlias . '.'  . ($options['association'] ?: $options['property_path']);
            },

            /**
             * Query path is the path of the field inside query : it's either an association,
             * or a base entity field (identified by it's property path).
             *
             * This expression will be used in both searching and ordering.
             *
             * Set a specific value if the property is a non mapped entity field.
             *
             * Can also be an array, in order to support searching and ordering over multiple fields for one column.
             */
            'query_path' => function(Options $options) {
                // Association query path
                if ($options['association']) {
                    return $options['alias'] . '.' . $options['property'];
                }
                return '_root.' . $options['property_path'];
            },

            /**
             * Path for ordering.
             */
            'order_path' => function(Options $options) {
                return $options['query_path'];
            },

            /**
             * Callback which filters query builder for filtered fields.
             */
            'filter_query_builder' => array($this, 'fieldFilter'),

            /**
             * Comparison operator for default filter
             *
             * @see fieldFilter
             */
            'filter_operator' => Query\Expr\Comparison::EQ,

            /**
             * Search operation for this field
             *
             * @see makeSearchExpr
             */
            'search_operation' => 'contains'
        ));

        $resolver->setAllowedTypes('sortable', 'bool');
        $resolver->setAllowedTypes('searchable', 'bool');

        // Association is the name of the relation for joining the query
        $resolver->setNormalizer('association', function(Options $options, $value) {
            if (true === $value) {
                return $options['name'];
            }
            return $value;
        });

        // Normalize search operation
        $resolver->setAllowedValues('search_operation', function($value) {
            if (is_array($value) && isset($value['operator']) && isset($value['format'])) {
                return true;
            } elseif (is_callable($value)) {
                return true;
            }

            return in_array($value, ['begin', 'contains', 'end', 'word']);
        });
        $resolver->setNormalizer('search_operation', function(Options $options, $value) {
            if (is_string($value)) {
                switch ($value) {
                    case 'begin' :
                        $format = '__SEARCH__%';
                        $operator = 'LIKE';
                        break;
                    case 'end' :
                        $format = '%__SEARCH__';
                        $operator = 'LIKE';
                        break;
                    case 'word' :   // UNTESTED (issues with sqlite)
                        $format = '[[:<:]]__SEARCH__';
                        $operator = 'REGEXP';
                        break;
                    case 'contains' :
                    default:
                        $format = '%__SEARCH__%';
                        $operator = 'LIKE';
                        break;
                }

                return array(
                    'operator' => $operator,
                    'format' => $format
                );
            }

            return $value;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(View $view, TableInterface $table, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'sortable' =>   $table->getOption('sortable'),
            'searchable' => $table->getOption('searchable')
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildFieldView(View $view, FieldInterface $field, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'sortable' => $options['sortable'],
            'searchable' => $options['searchable'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function normalizeTableOptions(array $options, array $fields)
    {
        // Update searchable and sortable options
        $options['searchable'] = $this->resolveFieldOption($fields, 'searchable');
        $options['sortable'] = $this->resolveFieldOption($fields, 'sortable');
        $options['filterable'] = $this->resolveFieldOption($fields, 'filterable');

        return $options;
    }

    /**
     * Resolves option from fields
     *
     * @param FieldInterface[] $fields
     * @param $option
     * @param mixed $expected
     * @return mixed
     */
    protected function resolveFieldOption(array $fields, $option, $expected = true)
    {
        foreach ($fields as $field) {
            if ($field->getOption($option) === $expected) {
                return $expected;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(TableInterface $table, Request $request = null)
    {
        // Do not handle if $table has data
        if ($table->hasData()) {
            return;
        }

        /** @var QueryBuilder $queryBuilder */
        if ($queryBuilder = $table->getOption('query_builder')) {
            // Filter Querybuilder if parameter in request
            $this->filterQueryBuilder($queryBuilder, $table);

            // Create the paginator
            $paginator = new Paginator($queryBuilder, $table->getOption('fetch_join_collection'));
            $page  = $table->getPage();
            $limit = $table->getLimit();

            $total = count($paginator);
            $pages = ceil($total / $limit);

            if ($table->getOption('pagination')) {
                if ($page > $pages) {
                    // Fix page out of range
                    $table->setPage($page = $pages);
                }

                $paginator->getQuery()
                    ->setFirstResult($limit * max(0, $page - 1))
                    ->setMaxResults($limit);
            }

            $table->setPages($pages);
            $table->setTotal($total);

            $table->setDataCallback(function() use ($paginator) {
                return $paginator->getIterator()->getArrayCopy();
            });
        }

    }

    /**
     *
     * @param QueryBuilder $queryBuilder
     * @param TableInterface $table
     */
    private function filterQueryBuilder(QueryBuilder $queryBuilder, TableInterface $table)
    {
        // Build associations
        $this->buildAssociations($queryBuilder, $table->getFields());

        // Search
        if ($table->getOption('searchable') && ($search = $table->getOption('search'))) {
            $queryBuilder->andWhere($this->makeSearchExpr($queryBuilder, $search, $table->getFields()));
        }

        // Sort
        if ($table->getOption('sortable') && ($sort = $table->getOption('sort'))) {
            $this->orderQueryBuilderBy($queryBuilder, $table, $sort);
        }

        // Filter params comes from FormFilterExtension, and contains the validated form data
        if ($filter = $table->getOption('filter_data')) {
            $this->buildFilter($queryBuilder, $table, $filter);
        }

        // Hand made filtering
        if ($filter = $table->getOption('query_builder_filter')) {
            /** @var callable $filter */
            $filter($queryBuilder, $table);
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param FieldInterface[] $fields
     */
    private function buildAssociations(QueryBuilder $queryBuilder, array $fields)
    {
        $assocToBuild = array(
            '_root' => true
        );

        // Make an association index
        $index = array();
        foreach ($fields as $field) {
            if ($field->getOption('association')) {
                $alias = $field->getOption('alias');
                $parent = $field->getOption('parent_alias');
                $path = $field->getOption('association_path');

                // Put the item in index
                $index[$alias] = array(
                    'join'   => $path,
                    'parent' => $parent
                );

                $assocToBuild[$alias] = false;
            }
        }

        // Index containing built associations' alias
        foreach ($index as $alias => $association) {
            if (!$assocToBuild[$alias]) {
                // Can build the association
                $this->buildQBAssociation($queryBuilder, $alias, $association, $index, $assocToBuild);
            }
        }
    }


    private function buildQBAssociation(
        QueryBuilder $queryBuilder,
        $alias,
        array $association,
        array $index,
        array &$built
    ) {
        // Add the parent first
        if (!$built[$association['parent']]) {
            $this->buildQBAssociation($queryBuilder, $association['parent'], $index[$association['parent']], $index, $built);
        }

        // Then the association itself
        $queryBuilder->addSelect($alias)->leftJoin($association['join'], $alias);

        $built[$alias] = true;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $search
     * @param array $fields
     * @return Query\Expr\Orx
     */
    private function makeSearchExpr(QueryBuilder $queryBuilder, $search, array $fields)
    {
        $expr = $queryBuilder->expr()->orX();

        $params = [];

        // Filtering
        /** @var Field $field */
        foreach ($fields as $field) {
            if ($field->getOption('searchable')) {
                $queryPaths = (array)$field->getOption('query_path');
                $operation = $field->getOption('search_operation');

                if (is_array($operation)) {
                    $operator = $operation['operator'];
                    $param = 'search_field_' . $field->getName();

                    $paramValue = str_replace('__SEARCH__', $search, $operation['format']);

                    $params[$param] = $paramValue;

                    foreach ($queryPaths as $queryPath) {
                        if ($operator === 'LIKE') {
                            $expr->add($this->fixQueryPath($queryPath, $field) . ' ' . $operator . ' :'.$param);
                        } else {
                            $expr->add($operator . '(' . $this->fixQueryPath($queryPath, $field) . ', :' . $param . ') = 1');
                        }
                    }
                } elseif (is_callable($operation)) {
                    foreach ($queryPaths as $queryPath) {
                        $expr->add($operation($queryBuilder, $queryPath, $search));
                    }
                }
            }
        }

        foreach ($params as $name => $value) {
            $queryBuilder->setParameter($name, $value);
        }

        return $expr;
    }

    /**
     * Sets the order by
     *
     * @param QueryBuilder $queryBuilder
     * @param TableInterface $table
     * @param $sort
     */
    private function orderQueryBuilderBy(QueryBuilder $queryBuilder, TableInterface $table, $sort)
    {
        if (!is_array($sort)) {
            $sort = array($sort => $table->getOption('order', 'DESC'));
        }

        foreach ($sort as $fieldName => $order) {
            // Check field is sortable
            $field = $table->getField($fieldName);
            if (!$field->getOption('sortable')) {
                throw new InvalidArgumentException("Field $fieldName is not sortable in table " . $table->getName());
            }

            foreach ((array)$field->getOption('order_path') as $orderBy) {
                $queryBuilder->addOrderBy($this->fixQueryPath($orderBy, $field), $order);
            }
        }
    }

    /**
     * @param string $path
     * @param FieldInterface $field
     *
     * @return string
     */
    private function fixQueryPath($path, FieldInterface $field)
    {
        if (false === strpos($path, '.')) {
            $path = $field->getOption('alias') . '.' . $path;
        }

        return $path;
    }

    /**
     *
     *
     * @param QueryBuilder $queryBuilder
     * @param TableInterface $table
     * @param array $filter
     */
    private function buildFilter(QueryBuilder $queryBuilder, TableInterface $table, array $filter)
    {
        foreach ($table->getFields() as $field) {
            if ($field->getOption('filterable')) {
                $name = $field->getName();
                $value = isset($filter[$name]) ? $filter[$name] : null;

                $closure = $field->getOption('filter_query_builder');
                call_user_func($closure, $queryBuilder, $field, $value);
            }
        }
    }

    /**
     * Default field filter implementation.
     *
     * @param QueryBuilder $queryBuilder
     * @param FieldInterface $field
     * @param $value
     */
    public function fieldFilter(QueryBuilder $queryBuilder, FieldInterface $field, $value)
    {
        if ($value) {
            $name = $field->getName();
            $path = $field->getOption('association_path');

            /**
             * Handle array or collections of items
             */
            if (is_array($value) || $value instanceof \Traversable) {

                $expr = $queryBuilder->expr()->orX();
                foreach ($value as $index => $v) {
                    $param = 'filter_' . $name . '_' . $index;
                    $queryBuilder->setParameter($param, $v);

                    if (is_object($v)) {
                        $expr->add(":$param MEMBER OF $path");
                    } else {
                        $expr->add("$path = :$param");
                    }
                }

                $queryBuilder->andWhere($expr);
            } else {
                /**
                 * Single value filter.
                 */
                $operator = $field->getOption('filter_operator');
                $comparison = new Query\Expr\Comparison($path, $operator, ':filter_' . $name);

                // Fix LIKE operators value (append and prepend %)
                // todo : parameterize this ?
                if ($operator == 'LIKE' || $operator == 'NOT LIKE') {
                    $value = '%' . $value . '%';
                }

                $queryBuilder
                    ->andWhere($comparison)
                    ->setParameter('filter_' . $name, $value)
                ;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDependency()
    {
        return array('core', 'form');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'orm';
    }
} 