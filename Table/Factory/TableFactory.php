<?php

namespace Nours\TableBundle\Table\Factory;

use Nours\TableBundle\Table\Extension\ExtensionInterface;
use Nours\TableBundle\Table\Builder\TableBuilder;
use Nours\TableBundle\Table\ResolvedType;
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

        // Resolve type if not
        if (!$type instanceof ResolvedType) {
            $this->tableTypes[$type->getName()] = $type = new ResolvedType($type, $this->getExtensions());
        }


        // Make options from type
        $options = $this->getOptions($type, $options);

        // Create the table from builder
        $table = $this->createBuilder($type, $options)->getTable();

        return $table;
    }


    protected function getOptions(TableTypeInterface $type, array $options)
    {
        // Configure options resolver
        $resolver = new OptionsResolver();
        $type->configureOptions($resolver);

        // Default options
        foreach ($this->getExtensions() as $extension) {
            $extension->configureOptions($resolver);
        }

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
        $builder = new TableBuilder($type, $this, $options);

        // Extensions build pass
        foreach ($this->getExtensions() as $extension) {
            $extension->buildTable($builder, $options);
        }

        // And build the fields
        $type->buildTable($builder, $options);

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

        // Make options from type
        $options = $this->getFieldOptions($name, $type, $options);

        return $type->createField($name, $options);
    }

    protected function getFieldOptions($name, FieldTypeInterface $type, $options)
    {
        // Configure options resolver
        $resolver = new OptionsResolver();

        $type->configureOptions($resolver);
        $resolver->setDefault('name', $name);

        // Default options
        foreach ($this->getExtensions() as $extension) {
            $extension->configureFieldOptions($resolver);
        }

        return $resolver->resolve($options);
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldType($name)
    {
        if (!isset($this->fieldTypes[$name])) {
            $this->throwBadFieldTypeException($name);
        }

        return $this->fieldTypes[$name];
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
                    if ($name) {
                        $this->sortedExtensions[] = $this->extensions[$name];
                        $stack[] = $name;
                    }
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