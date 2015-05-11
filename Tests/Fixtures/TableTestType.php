<?php

namespace Nours\TableBundle\Tests\Fixtures;

use Nours\TableBundle\Table\Builder\TableBuilder;
use Nours\TableBundle\Table\AbstractType;

class TableTestType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilder $builder)
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