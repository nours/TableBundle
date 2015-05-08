<?php

namespace Nours\TableBundle\Extension;


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
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'query_builder' => null,
            'search' => null,
            'sort' => null,
            'order' => null,
            'pager' => function(Options $options) {
                /** @var QueryBuilder $builder */
                if ($builder = $options['query_builder']) {

                    // Filter the query
                    if ($search = $options['search']) {
                        $this->filterQueryBuilder($builder, $search, $options['fields']);
                    }

                    // Order by
                    if ($sort = $options['sort']) {
                        $this->orderByQueryBuilder($builder, $sort, $options['order']);
                    }

                    // Make the pagerfanta
                    $adapter = new DoctrineORMAdapter($builder->getQuery());
                    $pager = new Pagerfanta($adapter);

                    return $pager->setMaxPerPage($options['limit'])->setCurrentPage($options['page']);
                }

                return null;
            }
        ));
        $resolver->setAllowedTypes(array(
            'query_builder' => array('Doctrine\ORM\QueryBuilder', 'null')
        ));
    }


    protected function filterQueryBuilder(QueryBuilder $builder, $search, $fields)
    {
        $alias  = $builder->getRootAliases()[0];

        // Filtering
        /** @var Field $field */
        foreach ($fields as $field) {
            if ($field->isSearchable()) {
                $builder->andWhere($alias . '.' . $field->getName() . ' LIKE : :search');
            }
            echo $field->getName();
        }

        $builder->setParameter('search', "%$search%");
    }


    protected function orderByQueryBuilder(QueryBuilder $builder, $sort, $order)
    {
        $alias  = $builder->getRootAliases()[0];

        $builder->orderBy("$alias.$sort", $order);
    }
} 