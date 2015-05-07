<?php

namespace Nours\TableBundle\Table\Builder;

use Nours\TableBundle\Field\Field;
use Nours\TableBundle\Table\TableInterface;

/**
 * Interface for table builders.
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
interface TableBuilderInterface
{
    /**
     * 
     * @param string $name
     * @param string $type
     * @param array $options
     * @return self
     */
    public function add($name, $type = null, array $options = array());

    /**
     * Returns fields
     *
     * @return Field[]
     */
    public function getFields();

    /**
     * Returns the table builded by this object.
     *
     * Data should be passed to the table for rendering.
     *
     * @return TableInterface
     */
    public function getTable();
}