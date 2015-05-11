<?php

namespace Nours\TableBundle\Extension;


use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Nours\TableBundle\Field\Field;
use Nours\TableBundle\Table\TableInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
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
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'query_builder' => null,
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
    public function loadTable(TableInterface $table, array $options)
    {
        /** @var QueryBuilder $qb */
        if ($qb = $options['query_builder']) {
            // Filter the query by search parameter
            if ($search = $options['search']) {
                $this->filterQueryBuilder($qb, $search, $table->getFields());
            }

            // Order by
            if ($sort = $options['sort']) {
                $this->orderByQueryBuilder($qb, $sort, $options['order']);
            }

            // Make the pagerfanta
            $adapter = new DoctrineORMAdapter($qb->getQuery());
            $pager = new Pagerfanta($adapter);

            $pager->setMaxPerPage($options['limit'])->setCurrentPage($options['page']);

            $results = $pager->getCurrentPageResults();

            // Results must be of type array for serialization
            if ($results instanceof \Traversable) {
                $results = iterator_to_array($results);
            }

            $table->setData($results);
            $table->setPages($pager->getNbPages());
            $table->setTotal($pager->getNbResults());
        }
    }

    /**
     * Filter the query by the full text search parameter.
     *
     * Builds a orX expression taking into account all searchable fields.
     *
     * @param QueryBuilder $builder
     * @param $search
     * @param $fields
     */
    protected function filterQueryBuilder(QueryBuilder $builder, $search, $fields)
    {
        $searchable = false;
        $expr = $builder->expr()->orX();
        $alias  = $builder->getRootAliases()[0];

        // Filtering
        /** @var Field $field */
        foreach ($fields as $field) {
            if ($field->isSearchable()) {
                $searchable = true;
                $expr->add($alias . '.' . $field->getName() . ' LIKE :search');
            }
        }

        if ($searchable) {
            $builder->where($expr);
            $builder->setParameter('search', "%$search%");
        }
    }


    protected function orderByQueryBuilder(QueryBuilder $builder, $sort, $order)
    {
        $alias  = $builder->getRootAliases()[0];

        $builder->orderBy("$alias.$sort", $order);
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