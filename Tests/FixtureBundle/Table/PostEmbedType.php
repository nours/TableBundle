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
use Nours\TableBundle\Field\Type\DateType;
use Nours\TableBundle\Field\Type\TextType;
use Nours\TableBundle\Table\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Class PostEmbedType
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class PostEmbedType extends AbstractType
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
            ->add('date', DateType::class, array(
                'sortable' => true,
                'searchable' => true,
                'property_path' => 'embed.date'
            ))
            ->add('author', TextType::class, array(
                'sortable' => true,
                'searchable' => true,
                'filterable' => true,
                'association' => true,
                'property' => 'name',
                'filter_type' => EntityType::class,
                'filter_options' => array(
                    'class' => 'Nours\TableBundle\Tests\FixtureBundle\Entity\Author',
                    'choice_label' => 'name'
                )
            ))
            ->add('author_email', TextType::class, array(
                'sortable' => true,
                'searchable' => true,
                'association' => 'author',
                'property' => 'email'
            ))
            ->add('isActive', BooleanType::class, array(
                'sortable' => true
            ))
            ->add('content', TextType::class, array(
                'searchable' => true
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
        return 'post_embed';
    }
}