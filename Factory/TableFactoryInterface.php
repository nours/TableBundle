<?php

namespace Nours\TableBundle\Factory;

use Nours\TableBundle\Table\TableTypeInterface;
use Nours\TableBundle\Field\FieldTypeInterface;
use Nours\TableBundle\Table\TableInterface;

interface TableFactoryInterface
{
    /**
     * Adds a table type into the factory.
     * 
     * @param TableTypeInterface $type
     */
    public function addTableType(TableTypeInterface $type);

    /**
     * Adds a field type into the factory.
     *
     * @param FieldTypeInterface $type
     */
    public function addFieldType(FieldTypeInterface $type);

    /**
     * Creates a new table.
     * 
     * @param string $type
     * @param array $options
     * @return TableInterface
     */
    public function createTable($type, array $options = array());
    
    /**
     * Creates a new field.
     * 
     * @param string $type
     * @param array $options
     */
    public function createField($name, $type, array $options = array());
}