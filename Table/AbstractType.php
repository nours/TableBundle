<?php

namespace Nours\TableBundle\Table;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Nours\TableBundle\Table\Builder\TableBuilder;

/**
 * Abstract type for tables.
 * 
 * Inherit from this base class in order to build new tables.
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
abstract class AbstractType implements TableTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        
    }

    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilder $builder)
    {
        
    }
}