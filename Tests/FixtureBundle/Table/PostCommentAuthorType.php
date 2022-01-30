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

use Nours\TableBundle\Field\Type\TextType;
use Nours\TableBundle\Table\AbstractType;
use Nours\TableBundle\Builder\TableBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PostCommentAuthorType
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class PostCommentAuthorType extends AbstractType
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
            ->add('page', TextType::class, array(
                'association' => true,
                'property' => 'content',
                'parent_alias' => 'author'
            ))
            ->add('author', TextType::class, array(
                'association' => true,
                'property'    => 'name',
                'sortable'    => true,
                'searchable'  => true,
                'query_path'  => array( 'lastname', 'author.name'  )
            ))
            ->add('authorEmail', TextType::class, array(
                'association' => 'author',
                'property' => 'email'
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
        return 'post_comment_author';
    }
}