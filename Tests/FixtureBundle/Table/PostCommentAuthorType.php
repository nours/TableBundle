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

use Nours\TableBundle\Table\AbstractType;
use Nours\TableBundle\Table\Builder\TableBuilder;
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
            ->add('id', 'text', array(
                'sortable' => true
            ))
            ->add('author', 'text', array(
                'association' => true,
                'property' => 'name'
            ))
            ->add('authorEmail', 'text', array(
                'association' => 'author',
                'property' => 'email'
            ))
            ->add('page', 'text', array(
                'association' => true,
                'property' => 'content',
                'association_parent' => 'author'
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
        return 'post_comment_author';
    }
}