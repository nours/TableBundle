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

use Nours\TableBundle\Table\Builder\TableBuilderInterface;
use Nours\TableBundle\Table\AbstractType;

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
    public function buildTable(TableBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'text')
            ->add('status', 'text')
            ->add('active', 'boolean')
            ->add('content', 'text')
        ;
    }

    public function getName()
    {
        return 'post';
    }
}