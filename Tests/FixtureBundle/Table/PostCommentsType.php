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
 * Class PostCommentsType
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class PostCommentsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilder $builder, array $options)
    {
        $builder->add('comments', 'collection', array(
            'displayed' => false,
            'association' => true,
            'property_path' => 'comment',
            'filter_type' => 'entity',
            'filter_options' => array(
                'class' => 'Nours\TableBundle\Tests\FixtureBundle\Entity\Comment',
                'multiple' => true,
                'property' => 'comment'
            )
        ));
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
        return 'post_comments';
    }
}