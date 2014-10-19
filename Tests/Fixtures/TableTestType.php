<?php

namespace Nours\TableBundle\Tests\Fixtures;

use Nours\TableBundle\Builder\TableBuilderInterface;
use Nours\TableBundle\Table\AbstractType;

class TableTestType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilderInterface $builder, array $options)
    {
        $builder->add('id', 'text', array(
            'searchable' => true
        ));
    }

    public function getName()
    {
        return 'test';
    }
} 