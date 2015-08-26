<?php

namespace Nours\TableBundle\Factory;

use Nours\TableBundle\Field\FieldInterface;
use Nours\TableBundle\Extension\ExtensionInterface;
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
     * Adds a table type into the factory.
     *
     * @param ExtensionInterface $extension
     */
    public function addTableExtension(ExtensionInterface $extension);

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
     * @param array $extensions
     */
    public function createField($name, $type, array $options = array(), array $extensions = array());

    /**
     * @return FieldTypeInterface
     */
    public function getFieldType($name);

    /**
     * @return ExtensionInterface[]
     */
    public function getExtensions();

    /**
     * @param TableTypeInterface $type
     * @return ExtensionInterface[]
     */
    public function getExtensionsForType(TableTypeInterface $type);

    /**
     * Normalize table options after collecting fields.
     *
     * @param array $options
     * @param FieldInterface[] $fields
     * @return array
     */
    public function normalizeTableOptions(array $options, array $fields);
}