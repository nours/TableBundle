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
            'field_path' => function(Options $options) {
                return $options['name'];
            },
            'association' => null,  // Association for field mapping
            'association_alias' => null,
            'parent_alias' => null,
            'query_path' => null,
            'property_path' => null
        ));

        $resolver->setAllowedTypes('sortable', 'bool');
        $resolver->setAllowedTypes('searchable', 'bool');
        $resolver->setNormalizer('association', function(Options $options, $value) {
            if (true === $value) {
                return $options['name'];
            }
            return $value;
        });
        $resolver->setNormalizer('association_alias', function(Options $options, $value) {
            if (empty($value) && ($association = $options['association'])) {
                return '_' . Inflector::tableize($association);
            }
            return $value;
        });
        $resolver->setNormalizer('parent_alias', function(Options $options, $value) {
            return $value ?: '_root';
        });
        $resolver->setNormalizer('query_path', function(Options $options, $value) {
            if (empty($value)) {
                $alias = $options['association_alias'] ?: '_root';
                $path = $options['field_path'];

                return $alias . '.' . $path;
            }
            return $value;
        });
        $resolver->setNormalizer('property_path', function(Options $options, $value) {
//            if (empty($value)) {
                $association = $options['association'];
                $path = $options['field_path'];

                return Inflector::tableize(($association ? $association . '.' : '') . $path);
//            }
//            return $value;
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
                    $this->buildAssociation($queryBuilder, $association, $field->getOption('association_alias'));

                    $assocBuild[$association] = true;
                }
            }
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $association
     * @param string $assocAlias
     * @param string|null $parentAlias
     */
    private function buildAssociation(QueryBuilder $queryBuilder, $association, $assocAlias, $parentAlias = null)
    {
        $parentAlias = $parentAlias ?: $queryBuilder->getRootAliases()[0];
        $queryBuilder->addSelect($assocAlias)->leftJoin($parentAlias .'.' . $association, $assocAlias);
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
     * @param QueryBuilder $queryBuilder
     * @param TableInterface $table
     * @param array $filter
     */
    private function buildFilter(QueryBuilder $queryBuilder, TableInterface $table, array $filter)
    {
        foreach ($filter as $name => $value) {
            $field = $table->getField($name);

            // todo : check this elsewhere
            if (!$field->getOption('filterable')) {
                throw new InvalidArgumentException("Field $name is not filterable in table " . $table->getName());
            }

            if ($value) {
                $path = is_object($value) ?
                    $field->getOption('parent_alias') . '.' . $field->getOption('association') :
                    $field->getOption('query_path');
                $queryBuilder->andWhere($path . " = :filter_$name");
                $queryBuilder->setParameter('filter_' . $name, $value);
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