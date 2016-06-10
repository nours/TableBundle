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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PostStatusType
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class PostStatusType extends AbstractType
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
            ->add('status', 'text', array(
                'sortable' => true,
                'searchable' => true,
                'filter_type' => ChoiceType::class,
                'filter_options' => array(
                    'choices' => array(
                        'new' => Post::STATUS_NEW,
                        'editing' => Post::STATUS_EDITING,
                        'published' => Post::STATUS_PUBLISHED,
                    ),
                    'choices_as_values' => true,
                    'multiple' => true,
                    'expanded' => true
                )
            ))
            ->add('content', 'text', array(
                'sortable' => true,
                'filter_type' => TextType::class,
                'filter_operator' => 'LIKE'
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
        return 'post_status';
    }
}