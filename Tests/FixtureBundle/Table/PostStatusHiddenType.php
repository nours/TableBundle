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

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Nours\TableBundle\Builder\TableBuilder;
use Nours\TableBundle\Field\FieldInterface;
use Nours\TableBundle\Table\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PostStatusHiddenType
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class PostStatusHiddenType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilder $builder, array $options)
    {
        $builder
            ->add('id', 'text', array(
                'sortable' => true
            ))
            ->add('status', 'hidden', array(
                'filter_type' => 'checkbox',
                'filter_options' => array(
                ),
                'filter_query_builder' => function(QueryBuilder $queryBuilder, FieldInterface $field, $value) {
                    $expected = $value ? array('new') : array('editing', 'published');

                    $criteria = Criteria::create();
                    $criteria->where($criteria->expr()->in('status', $expected));
                    $queryBuilder->addCriteria($criteria);
                }
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'class' => 'Nours\TableBundle\Tests\FixtureBundle\Entity\Post'
        ));
    }

    public function getName()
    {
        return 'post_status_hidden';
    }
}