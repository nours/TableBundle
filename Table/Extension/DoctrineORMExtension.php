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
                return $this->entityManager->getRepository($class)->createQueryBuilder('a');
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
            'sort_path'  => function(Options $options) {
                return $options['name'];
            },
        ));

        $resolver->setAllowedTypes('sortable', 'bool');
        $resolver->setAllowedTypes('searchable', 'bool');
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
        $table->setOption('searchable', $this->getFieldOption($table, 'searchable'));
        $table->setOption('sortable', $this->getFieldOption($table, 'sortable'));

        /** @var QueryBuilder $queryBuilder */
        if ($queryBuilder = $table->getOption('query_builder')) {
            // Filter Querybuilder if parameter in request
            $this->filterQueryBuilder($queryBuilder, $table);

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
        // Handle some search among fields
        if ($table->getOption('searchable') && ($search = $table->getOption('search'))) {
            $queryBuilder->where($this->makeSearchExpr($queryBuilder, $table->getFields()));
            $queryBuilder->setParameter('search', "%".$search."%");
        }

        if ($table->getOption('sortable') && ($sort = $table->getOption('sort'))) {
            $this->orderQueryBuilderBy($queryBuilder, $table, $sort);
        }

        if ($table->getOption('sortable') && ($sort = $table->getOption('sort'))) {
            $this->orderQueryBuilderBy($queryBuilder, $table, $sort);
        }

        // Filter params comes from FormFilterExtension, and contains the validated form data
        if ($filter = $table->getOption('filter_params')) {
            $this->buildFilter($queryBuilder, $table, $filter);
        }
    }


    private function buildFilter(QueryBuilder $queryBuilder, TableInterface $table, array $filter)
    {
        $alias  = $queryBuilder->getRootAliases()[0];

        foreach ($filter as $name => $value) {
            $field = $table->getField($name);

            // todo : check this elsewhere
//            if (!$field->getOption('filterable')) {
//                throw new InvalidArgumentException("Field $name is not filterable in table " . $table->getName());
//            }

            $queryBuilder->andWhere("$alias.$name = :$name");
            $queryBuilder->setParameter($name, $value);

        }
//        var_dump($filter);die;
    }

    private function orderQueryBuilderBy(QueryBuilder $queryBuilder, TableInterface $table, $fieldName)
    {
        $alias  = $queryBuilder->getRootAliases()[0];

        // Check field is sortable
        $field = $table->getField($fieldName);
        if (!$field->getOption('sortable')) {
            throw new InvalidArgumentException("Field $fieldName is not sortable in table " . $table->getName());
        }

        $sort  = $field->getOption('sort_path');
        $order = $table->getOption('order', 'DESC');

        $queryBuilder->orderBy("$alias.$sort", $order);
    }


    private function makeSearchExpr(QueryBuilder $queryBuilder, array $fields)
    {
        $expr = $queryBuilder->expr()->orX();
        $alias  = $queryBuilder->getRootAliases()[0];

        // Filtering
        /** @var Field $field */
        foreach ($fields as $field) {
            if ($field->isSearchable()) {
                $expr->add($alias . '.' . $field->getName() . ' LIKE :search');
            }
        }

        return $expr;
    }

    protected function getFieldOption(TableInterface $table, $option, $expected = true)
    {
        if (($value = $table->getOption($option)) !== null) {
            // Value has been set in table option
            return $value;
        }

        foreach ($table->getFields() as $field) {
            if ($field->getOption($option) === $expected) {
                $table->setOption('searchable', $expected);
                return $expected;
            }
        }

        return null;
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