<?php

namespace Nours\TableBundle\Factory;

use Nours\TableBundle\Extension\ExtensionInterface;
use Nours\TableBundle\Table\Builder\TableBuilder;
use Nours\TableBundle\Table\TableTypeInterface;
use Nours\TableBundle\Field\FieldTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                $this->throwBadTableTypeException($type);
            }
            
            $type = $this->tableTypes[$type];
        }
        
        return $this->createBuilder($type)->getTable($options);
    }

    /**
     * Creates the table builder for a table type.
     *
     * @param TableTypeInterface $type
     * @return TableBuilder
     */
    protected function createBuilder(TableTypeInterface $type)
    {
        // Configure options resolver
        $resolver = $this->getOptionsResolver();

        // Default options
        foreach ($this->getExtensions() as $extension) {
            $extension->setDefaultOptions($resolver);
        }
        $type->setDefaultOptions($resolver);

        $builder = new TableBuilder($type->getName(), $this, $resolver);

        // And build the fields
        $type->buildTable($builder);

        return $builder;
    }

    /**
     * @return OptionsResolver
     */
    protected function getOptionsResolver()
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults(array(
            'fields'  => null,
            'page'    => 1,
            'limit'   => 10,
            'pages'   => null,
            'total'   => null,
            'data'    => null,
            'url'     => null,
//            'row_style' => false
        ));

        return $resolver;
    }
    
    /**
     * {@inheritdoc}
     */
    public function createField($name, $type, array $options = array())
    {
        if (!$type instanceof FieldTypeInterface) {
            if (!isset($this->fieldTypes[$type])) {
                $this->throwBadFieldTypeException($type);
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

    /**
     * @param $type
     * @throw \InvalidArgumentException
     */
    private function throwBadTableTypeException($type)
    {
        $message = "Table type '%s' is not registered in factory. " .
            "Maybe you forgot to declare service using nours_table.table_type tag or there is a typo in type name. " .
            "Known type are (%s)";

        throw new \InvalidArgumentException(sprintf($message, $type, implode(', ', array_keys($this->tableTypes))));
    }

    /**
     * @param $type
     * @throw \InvalidArgumentException
     */
    private function throwBadFieldTypeException($type)
    {
        $message = "Unknown field type '%s'. " .
            "Maybe you forgot to declare service using nours_table.field_type tag or there is a typo in type name. " .
            "Known type are (%s)";

        throw new \InvalidArgumentException(sprintf($message, $type, implode(', ', array_keys($this->fieldTypes))));
    }
}