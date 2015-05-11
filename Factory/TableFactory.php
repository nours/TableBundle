<?php

namespace Nours\TableBundle\Factory;

use Nours\TableBundle\Extension\ExtensionInterface;
use Nours\TableBundle\Table\Builder\TableBuilder;
use Nours\TableBundle\Table\TableInterface;
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
     * @var ExtensionInterface[]
     */
    private $extensions = array();

    /**
     * @var ExtensionInterface[]
     */
    private $sortedExtensions;

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
        // Erase any previous sort
        $this->sortedExtensions = null;

        $this->extensions[$extension->getName()] = $extension;
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

        // Make options from type
        $options = $this->getOptions($type, $options);

        // Create the table from builder
        $table = $this->createBuilder($type, $options)->getTable();

        // Finish by loading data
        $this->loadTableData($table, $options);
        
        return $table;
    }


    protected function loadTableData(TableInterface $table, array $options)
    {
        foreach (array_reverse($this->getExtensions()) as $extension) {
            /** @var ExtensionInterface $extension */
            $extension->loadTable($table, $options);

            // Stop when the first extension has loaded data
            if ($table->getData()) {
                return;
            }
        }
    }


    protected function getOptions(TableTypeInterface $type, array $options)
    {
        // Configure options resolver
        $resolver = new OptionsResolver();

        // Default options
        foreach ($this->getExtensions() as $extension) {
            $extension->setDefaultOptions($resolver);
        }
        $type->setDefaultOptions($resolver);

        return $resolver->resolve($options);
    }

    /**
     * Creates the table builder for a table type.
     *
     * @param TableTypeInterface $type
     * @param array $options
     * @return TableBuilder
     */
    protected function createBuilder(TableTypeInterface $type, array $options)
    {
        $builder = new TableBuilder($type->getName(), $this, $options);

        // Extensions build pass
        foreach ($this->getExtensions() as $extension) {
            $extension->buildTable($builder, $options);
        }

        // And build the fields
        $type->buildTable($builder);

        // Extensions build pass
        foreach ($this->getExtensions() as $extension) {
            $extension->finishTable($builder, $options);
        }

        return $builder;
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
        if (empty($this->sortedExtensions)) {
            $this->sortExtensions();
        }

        return $this->sortedExtensions;
    }

    /**
     * Sort the extensions by dependency
     */
    private function sortExtensions()
    {
        $index = array();
        foreach ($this->extensions as $extension) {
            $dep = $extension->getDependency() ?: '';
            $index[$dep][] = $extension->getName();
        }

        // Ensure extensions are loaded in order
        $this->sortedExtensions = array();
        $stack = array('');
        while (!empty($stack)) {
            $current = array_pop($stack);
            if (isset($index[$current])) {
                foreach ($index[$current] as $name) {
                    $this->sortedExtensions[] = $this->extensions[$name];
                    $stack[] = $name;
                }
            }
        }
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