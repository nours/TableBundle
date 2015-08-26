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
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('id', 'text', array(
                'sortable' => true
            ))
            ->add('date', 'date', array(
                'sortable' => true,
                'searchable' => true,
                'property_path' => 'embed.date'
            ))
            ->add('author', 'text', array(
                'sortable' => true,
                'searchable' => true,
                'filterable' => true,
                'association' => true,
                'property' => 'name',
                'filter_type' => 'entity',
                'filter_options' => array(
                    'class' => 'Nours\TableBundle\Tests\FixtureBundle\Entity\Author',
                    'property' => 'name'
                )
            ))
            ->add('author_email', 'text', array(
                'sortable' => true,
                'searchable' => true,
                'association' => 'author',
                'property' => 'email'
            ))
            ->add('isActive', 'boolean', array(
                'sortable' => true
            ))
            ->add('content', 'text', array(
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

    public function getName()
    {
        return 'post_embed';
    }
}