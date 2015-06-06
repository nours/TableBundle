<?php

namespace Nours\TableBundle\Table\Extension;


use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Nours\TableBundle\Field\Field;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
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
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $defaultPager = function(Options $options) {
            if ($qb = $options['query_builder']) {
                $adapter = new DoctrineORMAdapter($qb->getQuery());
                $pager = new Pagerfanta($adapter);

                $pager->setMaxPerPage($options['limit'])->setCurrentPage($options['page']);

                return $pager;
            }

            return null;
        };

        $qbNormalizer = function(Options $options, $queryBuilder) {
            if ($queryBuilder) {
//                // Filter the query by search parameter
//                if ($search = $options['search']) {
//                    $this->filterQueryBuilder($queryBuilder, $search, $table->getFields());
//                }
//
//                // Order by
//                if ($sort = $options['sort']) {
//                    $this->orderByQueryBuilder($qb, $sort, $options['order']);
//                }
            }
        };

        $resolver->setDefaults(array(
            'pager' => $defaultPager,
            'query_builder' => null,
            'search' => null,
            'sort' => null,
            'order' => null
        ));
        $resolver->setAllowedTypes(array(
            'query_builder' => array('Doctrine\ORM\QueryBuilder', 'null')
        ));
        $resolver->setNormalizer('query_builder', $qbNormalizer);
    }

//    /**
//     * {@inheritdoc}
//     */
//    public function buildView(View $view, TableInterface $table)
//    {
//        $options = $table->getOptions();
//
//        /** @var QueryBuilder $qb */
//        if ($qb = $table->getOption(['query_builder'])) {
//
//            // Make the pagerfanta
//            $adapter = new DoctrineORMAdapter($qb->getQuery());
//            $pager = new Pagerfanta($adapter);
//
//            $pager->setMaxPerPage($options['limit'])->setCurrentPage($options['page']);
//
//            $results = $pager->getCurrentPageResults();
//
//            // Results must be of type array for serialization
//            if ($results instanceof \Traversable) {
//                $results = iterator_to_array($results);
//            }
//
//            $table->setData($results);
//            $table->setPages($pager->getNbPages());
//            $table->setTotal($pager->getNbResults());
//        }
//    }

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