<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Tests\FixtureBundle\Table;

use Doctrine\ORM\QueryBuilder;
use Nours\TableBundle\Builder\TableBuilder;
use Nours\TableBundle\Field\Type\TextType;
use Nours\TableBundle\Table\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SearchableType
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class SearchableType extends AbstractType
{
    public function buildTable(TableBuilder $builder, array $options)
    {
        $builder
            ->add('id', TextType::class)
            ->add('searchBegin', TextType::class, [
                'searchable' => true,
                'search_operation' => 'begin'
            ])
            ->add('searchInside', TextType::class, [
                'searchable' => true,
                'search_operation' => 'contains'
            ])
            ->add('searchEnd', TextType::class, [
                'searchable' => true,
                'search_operation' => 'end'
            ])
            ->add('searchWord', TextType::class, [
                'searchable' => true,
                'search_operation' => array(
                    'operator' => 'LIKE',
                    'format' => '%__SEARCH__%'
                )
//                'search_operation' => 'word'
            ])
            ->add('searchCustom', TextType::class, [
                'searchable' => true,
                'search_operation' => function(QueryBuilder $queryBuilder, $path, $search) {
                    return $queryBuilder->expr()->like($path, '%' . $search);
                }
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'class' => 'Nours\TableBundle\Tests\FixtureBundle\Entity\Searchable',
            'query'
        ));
    }

    public function getBlockPrefix()
    {
        return 'searchable';
    }
}