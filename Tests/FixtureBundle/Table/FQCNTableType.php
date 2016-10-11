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
use Nours\TableBundle\Tests\FixtureBundle\Field\FQCNFieldType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FQCNTableType
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class FQCNTableType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilder $builder, array $options)
    {
        $builder
            ->add('id', FQCNFieldType::class, array(
                'sortable' => true
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'class' => Post::class
        ));
    }
}