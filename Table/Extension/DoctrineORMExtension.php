<?php

namespace Nours\TableBundle\Table\Extension;


use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Nours\TableBundle\Field\Field;
use Nours\TableBundle\Field\FieldInterface;
use Nours\TableBundle\Table\TableInterface;
use Nours\TableBundle\Table\View;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
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
            'sort' => null,
            'order' => null
        ));
        $resolver->setAllowedTypes(array(
            'query_builder' => array('Doctrine\ORM\QueryBuilder', 'null')
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureFieldOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'sortable'   => false,
            'searchable' => false,
            'filterable' => false,
            'association' => null,  // Association for field mapping
            'property' => null,     // The property in the association
            'property_path' => function(Options $options) {
                $association = $options['association'];
                $property    = $options['property'];
                if ($association) {
                    // The property path now defaults to association.property
                    if (!$property) {
                        throw new \DomainException("property must be set for field " . $options['name']);
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
            'parent_alias' => function(Options $options) {
                return '_root';
            },

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
             */
            'query_path' => function(Options $options) {
                // Association query path
                if ($options['association']) {
                    return $options['alias'] . '.' . $options['property'];
                }
                return '_root.' . $options['property_path'];
            }
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
        /** @var QueryBuilder $queryBuilder */
        if ($queryBuilder = $table->getOption('query_builder')) {
            // Filter Querybuilder if parameter in request
            $this->filterQueryBuilder($queryBuilder, $table);

//            var_dump($queryBuilder->getQuery()->getSQL());
            // Create the pager
            $adapter = new DoctrineORMAdapter($queryBuilder->getQuery());
            $pager = new Pagerfanta($adapter);

            $pager->setMaxPerPage($table->getLimit())->setCurrentPage($table->getPage());

            // Propage pager to pagerfanta extension
            $table->setOption('pager', $pager);
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
            $queryBuilder->andWhere($this->makeSearchExpr($queryBuilder, $table->getFields()));
            $queryBuilder->setParameter('search', "%".$search."%");
        }

        // Sort
        if ($table->getOption('sortable') && ($sort = $table->getOption('sort'))) {
            $this->orderQueryBuilderBy($queryBuilder, $table, $sort);
        }

        // Filter params comes from FormFilterExtension, and contains the validated form data
        if ($filter = $table->getOption('filter_data')) {
            $this->buildFilter($queryBuilder, $table, $filter);
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
     * @param array $fields
     * @return Query\Expr\Orx
     */
    private function makeSearchExpr(QueryBuilder $queryBuilder, array $fields)
    {
        $expr = $queryBuilder->expr()->orX();

        // Filtering
        /** @var Field $field */
        foreach ($fields as $field) {
            if ($field->getOption('searchable')) {
                $expr->add($field->getOption('query_path') . ' LIKE :search');
            }
        }

        return $expr;
    }

    /**
     * Sets the order by
     *
     * @param QueryBuilder $queryBuilder
     * @param TableInterface $table
     * @param $fieldName
     */
    private function orderQueryBuilderBy(QueryBuilder $queryBuilder, TableInterface $table, $fieldName)
    {
        // Check field is sortable
        $field = $table->getField($fieldName);
        if (!$field->getOption('sortable')) {
            throw new InvalidArgumentException("Field $fieldName is not sortable in table " . $table->getName());
        }

        $order = $table->getOption('order', 'DESC');

        $queryBuilder->orderBy($field->getOption('query_path'), $order);
    }

    /**
     *
     *
     * @param QueryBuilder $qb
     * @param TableInterface $table
     * @param array $filter
     */
    private function buildFilter(QueryBuilder $qb, TableInterface $table, array $filter)
    {
        foreach ($filter as $name => $value) {
            $field = $table->getField($name);

            // todo : check this elsewhere
            if (!$field->getOption('filterable')) {
                throw new InvalidArgumentException("Field $name is not filterable in table " . $table->getName());
            }

            if ($value) {
                $path = $field->getOption('association_path');

                /**
                 * Handle array or collections of items
                 */
                if (is_array($value) || $value instanceof \Traversable) {

                    $expr = $qb->expr()->orX();
                    foreach ($value as $index => $v) {
                        $param = 'filter_' . $name . '_' . $index;
                        $qb->setParameter($param, $v);

                        if (is_object($v)) {
                            $expr->add(":$param MEMBER OF $path");
                        } else {
                            $expr->add("$path = :$param");
                        }
                    }

                    $qb->andWhere($expr);
                } else {
                    /**
                     * Otherwise, handles an equality operation
                     */
                    $qb->andWhere($path . " = :filter_$name");
                    $qb->setParameter('filter_' . $name, $value);
                }
            }

        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDependency()
    {
        return 'pagerfanta';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'orm';
    }
} 