<?php

namespace Nours\TableBundle\Factory;

use Nours\TableBundle\Table\ExtensionInterface;
use Nours\TableBundle\Table\TableTypeInterface;
use Nours\TableBundle\Field\FieldTypeInterface;

class TableFactory implements TableFactoryInterface
{
    /**
     * @var array
     */
    private $tableTypes = array();
    
    /**
     * @var array
     */
    private $fieldTypes = array();

    /**
     * @var array
     */
    private $extensions = array();

    /**
     * {@inheritdoc}
     */
    public function addTableType(TableTypeInterface $type)
    {
        $this->tableTypes[$type->getName()] = $type;
    }
    
    /**
     * {@inheritdoc}
     */
    public function addFieldType(FieldTypeInterface $type)
    {
        $this->fieldTypes[$type->getName()] = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function addTableExtension(ExtensionInterface $extension)
    {
        $this->extensions[] = $extension;
    }
    
    /**
     * {@inheritdoc}
     */
    public function createTable($type, array $options = array())
    {
        if (!$type instanceof TableTypeInterface) {
            if (!isset($this->tableTypes[$type])) {
                throw new \InvalidArgumentException("Table type '$type' is not registered in factory. Maybe you forgot to declare service using nours_table.table_type tag or there is a typo in type name.");
            }
            
            $type = $this->tableTypes[$type];
        }
        
        $builder = $type->createBuilder($type->getName(), $this, $options);
        
        return $builder->getTable();
    }
    
    /**
     * {@inheritdoc}
     */
    public function createField($name, $type, array $options = array())
    {
        if (!$type instanceof FieldTypeInterface) {
            if (!isset($this->fieldTypes[$type])) {
                throw new \InvalidArgumentException("Wrong field type '$type'. The known types are (".implode(', ', array_keys($this->fieldTypes)).").");
            }
            
            $type = $this->fieldTypes[$type];
        }
        
        return $type->createField($name, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        return $this->extensions;
    }
}