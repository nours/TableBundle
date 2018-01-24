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

/**
 * Class PagerType
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class PagerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilder $builder, array $options)
    {
        $builder
            ->add('id', TextType::class)
        ;
    }

    public function getExtension()
    {
        return 'core';
    }

    public function getBlockPrefix()
    {
        return 'pager';
    }
}