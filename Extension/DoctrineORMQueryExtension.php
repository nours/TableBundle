<?php

namespace Nours\TableBundle\Extension;


use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Nours\TableBundle\Field\Field;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Builds a pager upon query.
 *
 * Implements search filters.
 *
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class DoctrineORMQueryExtension extends PagerfantaExtension
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'query_builder' => null,
            'search' => null,
            'pager' => function(Options $options) {
                /** @var QueryBuilder $builder */
                if ($builder = $options['query_builder']) {

                    // Filter the query
                    if ($search = $options['search']) {
                        $this->filterQueryBuilder($builder, $search, $options['fields']);
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


    private function filterQueryBuilder(QueryBuilder $builder, $search, $fields)
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
} 