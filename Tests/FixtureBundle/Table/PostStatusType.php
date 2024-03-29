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
use Nours\TableBundle\Field\Type\TextType;
use Nours\TableBundle\Table\AbstractType;
use Nours\TableBundle\Tests\FixtureBundle\Entity\Post;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType as FormTextType;
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
            ->add('id', TextType::class, array(
                'sortable' => true
            ))
            ->add('status', TextType::class, array(
                'sortable' => true,
                'searchable' => true,
                'filter_type' => ChoiceType::class,
                'filter_options' => array(
                    'choices' => array(
                        'new' => Post::STATUS_NEW,
                        'editing' => Post::STATUS_EDITING,
                        'published' => Post::STATUS_PUBLISHED,
                    ),
                    'multiple' => true,
                    'expanded' => true
                )
            ))
            ->add('content', TextType::class, array(
                'sortable' => true,
                'filter_type' => FormTextType::class,
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

    public function getBlockPrefix(): string
    {
        return 'post_status';
    }
}