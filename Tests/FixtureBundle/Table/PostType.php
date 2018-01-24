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
use Nours\TableBundle\Field\Type\BooleanType;
use Nours\TableBundle\Field\Type\LabelType;
use Nours\TableBundle\Field\Type\PrototypeType;
use Nours\TableBundle\Field\Type\TextType;
use Nours\TableBundle\Table\AbstractType;
use Nours\TableBundle\Tests\FixtureBundle\Entity\Post;
use Nours\TableBundle\Tests\FixtureBundle\Field\ExtendedTextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PostType
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class PostType extends AbstractType
{
    /**
     * DI check
     *
     * @param array $arg
     */
    public function __construct(array $arg)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilder $builder, array $options)
    {
        $builder
            ->add('id', TextType::class, array(
                'sortable' => true
            ))
            ->add('status', LabelType::class, array(
                'sortable' => true,
                'searchable' => true,
                'filter_type' => ChoiceType::class,
                'filter_options' => array(
                    'choices' => array(
                        'new' => Post::STATUS_NEW,
                        'editing' => Post::STATUS_EDITING,
                        'published' => Post::STATUS_PUBLISHED,
                    )
                ),
                'labels' => array(
                    Post::STATUS_NEW => array('style' => 'danger'),
                    Post::STATUS_EDITING => array('style' => 'warning'),
                    Post::STATUS_PUBLISHED => array('style' => 'success')
                )
            ))
            ->add('isActive', BooleanType::class, array(
                'sortable' => true
            ))
            ->add('content', ExtendedTextType::class, array(
                'searchable' => true,
                'truncate' => 20
            ))
            ->add('prototype', PrototypeType::class, array(
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

    public function getBlockPrefix()
    {
        return 'post';
    }
}