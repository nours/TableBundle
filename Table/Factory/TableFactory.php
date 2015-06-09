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
            $this->tableTypes[$type->getName()] = $type = new ResolvedType($type, $this->getExtensionsForType($type));
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

        // Default options
        foreach ($this->getExtensions() as $extension) {
            $extension->configureOptions($resolver);
        }

        // Type configuration should prevail over extensions
        $type->configureOptions($resolver);
        $resolver->setDefault('name', $type->getName());

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
        $extensions = $this->getExtensionsForType($type);

        // Extensions build pass
        foreach ($extensions as $extension) {
            $extension->buildTable($builder, $options);
        }

        // And build the fields
        $type->buildTable($builder, $options);

        // Extensions build pass
        foreach ($extensions as $extension) {
            $extension->finishTable($builder, $options);
        }

        return $builder;
    }

    /**
     * {@inheritdoc}
     */
    public function createField($name, $type, array $options = array(), array $extensions = array())
    {
        if (!$type instanceof FieldTypeInterface) {
            if (!isset($this->fieldTypes[$type])) {
                $this->throwBadFieldTypeException($type);
            }
            
            $type = $this->fieldTypes[$type];
        }

        // Configure options resolver
        $resolver = new OptionsResolver();

        // Default options
        $extensions = $extensions ?: $this->getExtensions();
        foreach ($extensions as $extension) {
            $extension->configureFieldOptions($resolver);
        }

        $type->configureOptions($resolver);
        $resolver->setDefault('name', $name);

        $options = $resolver->resolve($options);

        return $type->createField($name, $options);
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
     * {@inheritdoc}
     */
    public function getExtensionsForType(TableTypeInterface $type)
    {
        // Type extensions are resolved
        if ($type instanceof ResolvedType) {
            return $type->getExtensions();
        }

        // Return all extensions if no name provided
        if (($name = $type->getExtension()) === null) {
            return $this->getExtensions();
        }

        // Find ordered extensions list for specific name
        $extensions = array();
        $this->findExtensions($extensions, $name);

        // Strip off index
        return array_values($extensions);
    }

    /**
     * Finds recursively
     *
     * @param $extensions
     * @param $name
     */
    private function findExtensions(&$extensions, $name)
    {
        $extension = $this->getExtension($name);

        // Put deps first
        if (($dep = $extension->getDependency()) && !isset($extensions[$dep])) {
            $this->findExtensions($extensions, $dep);
        }

        $extensions[$name] = $extension;
    }

    /**
     * @param $name
     * @return ExtensionInterface
     */
    private function getExtension($name)
    {
        if (!isset($this->extensions[$name])) {
            throw new \InvalidArgumentException("There is no extension called $name in (" . implode(', ', array_keys($this->extensions)) . ")");
        }

        return $this->extensions[$name];
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