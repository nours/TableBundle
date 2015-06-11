<?php

namespace Nours\TableBundle\Table\Extension;


use Doctrine\Common\Inflector\Inflector;
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

            /**
             * Alias of the association
             *
             * ex : _author
             */
            'alias' => function(Options $options) {
                if ($association = $options['association']) {
                    return '_' . Inflector::tableize($association);
                }
                return '_root';
            },

            /**
             * Parent alias of the association
             *
             * always : _root until other levels are handled
             */
            'parent_alias' => '_root',

            /**
             * Overrides CoreExtension full_path option to include association part as a full
             * property_path (which should remain based on the associated relation)
             *
             * ex : author.name
             */
            'full_path' => function(Options $options) {
                // Override full path to include association name if any
                $association = $options['association'];

                return ($association ? $association . '.' : '') . $options['property_path'];
            },

            /**
             * Self path is the path to current object in query perspective.
             *
             * It shall be used for filtering as objects
             *
             * ex : _root.author
             */
            'self_path' => function(Options $options) {
                return $options['parent_alias'] . '.' . ($options['association'] ?: $options['property_path']);
            },

            'query_path' => function(Options $options, $value) {
                // Association query path
                if (empty($value)) {
                    return $options['alias'] . '.' . $options['property_path'];
                }
                return $value;
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
    public function handle(TableInterface $table, Request $request = null)
    {
        // Update searchable and sortable options
        $table->setOption('searchable', $this->resolveFieldOption($table, 'searchable'));
        $table->setOption('sortable', $this->resolveFieldOption($table, 'sortable'));
        $table->setOption('filterable', $this->resolveFieldOption($table, 'filterable'));

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
            $queryBuilder->where($this->makeSearchExpr($queryBuilder, $table->getFields()));
            $queryBuilder->setParameter('search', "%".$search."%");
        }

        // Sort
        if ($table->getOption('sortable') && ($sort = $table->getOption('sort'))) {
            $this->orderQueryBuilderBy($queryBuilder, $table, $sort);
        }

        // Filter params comes from FormFilterExtension, and contains the validated form data
        if ($filter = $table->getOption('filter_params')) {
            $this->buildFilter($queryBuilder, $table, $filter);
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param FieldInterface[] $fields
     */
    private function buildAssociations(QueryBuilder $queryBuilder, array $fields)
    {
        $assocBuild = array();

        foreach ($fields as $field) {
            if ($association = $field->getOption('association')) {

                // Only one level supported by now
                if (!isset($assocBuild[$association])) {
                    $alias = $field->getOption('alias');

                    $queryBuilder->addSelect($alias)->leftJoin($field->getOption('self_path'), $alias);

                    $assocBuild[$association] = true;
                }
            }
        }
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
                $path = $field->getOption('self_path');

                /**
                 * Handle array or collections of items
                 */
                if (is_array($value) || $value instanceof \Traversable) {

                    $expr = $qb->expr()->orX();
                    foreach ($value as $index => $v) {
                        $param = 'filter_' . $name . '_' . $index;
                        $expr->add(":$param MEMBER OF $path");
                        $qb->setParameter($param, $v);
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