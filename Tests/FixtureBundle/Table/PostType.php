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

use Nours\TableBundle\Builder\TableBuilder;
use Nours\TableBundle\Table\AbstractType;
use Nours\TableBundle\Tests\FixtureBundle\Entity\Post;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PostType
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class PostType extends AbstractType
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
            ->add('status', 'label', array(
                'sortable' => true,
                'searchable' => true,
                'filter_type' => 'choice',
                'filter_options' => array(
                    'choices' => array(
                        Post::STATUS_NEW => 'new',
                        Post::STATUS_EDITING => 'editing',
                        Post::STATUS_PUBLISHED => 'published',
                    )
                ),
                'labels' => array(
                    Post::STATUS_NEW => array('style' => 'danger'),
                    Post::STATUS_EDITING => array('style' => 'warning'),
                    Post::STATUS_PUBLISHED => array('style' => 'success')
                )
            ))
            ->add('isActive', 'boolean', array(
                'sortable' => true
            ))
            ->add('content', 'extended_text', array(
                'searchable' => true,
                'truncate' => 20
            ))
            ->add('prototype', 'prototype', array(
                'prototype' => 'Post __id__ (__status__)',
                'mappings' => array(
                    '__id__' => 'id',
                    '__status__' => 'status'
                )
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
        return 'post';
    }
}