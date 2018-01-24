<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Factory;

use Nours\TableBundle\Extension\ExtensionInterface;
use Nours\TableBundle\Builder\TableBuilder;
use Nours\TableBundle\Table\ResolvedType;
use Nours\TableBundle\Table\TableTypeInterface;
use Nours\TableBundle\Field\FieldTypeInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableFactory implements TableFactoryInterface
{
    /**
     * @var ExtensionInterface[]
     */
    private $extensions = array();

    /**
     * @var ExtensionInterface[]
     */
    private $sortedExtensions;

    /**
     * @var ContainerInterface
     */
    private $tableTypeLocator;

    /**
     * @var ContainerInterface
     */
    private $fieldTypeLocator;

    /**
     * @var ResolvedType[]
     */
    private $resolveTableTypes;

    /**
     * TableFactory constructor.
     *
     * @param ContainerInterface $tableTypeLocator
     * @param ContainerInterface $fieldTypeLocator
     */
    public function __construct(ContainerInterface $tableTypeLocator, ContainerInterface $fieldTypeLocator)
    {
        $this->tableTypeLocator = $tableTypeLocator;
        $this->fieldTypeLocator = $fieldTypeLocator;
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
            $type = $this->getTableType($type);
        }

        $typeName = get_class($type);

        // Resolve type if not already resolved
        if (isset($this->resolveTableTypes[$typeName])) {
            $type = $this->resolveTableTypes[$typeName];
        } else {
            $this->resolveTableTypes[$typeName] = $type = new ResolvedType($type, $this->getExtensionsForType($type));
        }

        // Make options from type
        $options = $this->getOptions($type, $options);

        // Create the table from builder
        $table = $this->createBuilder($type, $options)->getTable();

        return $table;
    }


    protected function getOptions(ResolvedType $type, array $options)
    {
        // Configure options resolver
        $resolver = new OptionsResolver();

        // Default options
        foreach ($type->getExtensions() as $extension) {
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
     * @param ResolvedType $type
     * @param array $options
     * @return TableBuilder
     */
    protected function createBuilder(ResolvedType $type, array $options)
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
            $type = $this->getFieldType($type);
        }

        // Configure options resolver
        $resolver = new OptionsResolver();

        // Default options
        $extensions = $extensions ?: $this->getExtensions();
        foreach ($extensions as $extension) {
            $extension->configureFieldOptions($resolver);
        }

        // Type hierarchy
        foreach ($this->getFieldTypeAncestors($type) as $ancestor) {
            $ancestor->configureOptions($resolver);
        }

        $type->configureOptions($resolver);
        $resolver->setDefault('name', $name);

        $options = $resolver->resolve($options);

        return $type->createField($name, $options, $this->getFieldTypeAncestors($type));
    }

    /**
     * @param FieldTypeInterface $type
     * @return FieldTypeInterface[]
     */
    private function getFieldTypeAncestors(FieldTypeInterface $type)
    {
        $ancestors = array();

        $parent = $type->getParent();
        while ($parent) {
            $parentType = $this->getFieldType($parent);
            $ancestors[] = $parentType;

            $parent = $parentType->getParent();
        }

        return $ancestors;
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldType($name)
    {
        if ($this->fieldTypeLocator->has($name)) {
            $type = $this->fieldTypeLocator->get($name);
        } elseif (class_exists($name)) {
            if (!in_array('Nours\TableBundle\Field\FieldTypeInterface', class_implements($name))) {
                throw new \InvalidArgumentException(sprintf("Field type %s must implement FieldTypeInterface", $name));
            }
            $type = new $name;
        } else {
            throw new \InvalidArgumentException(sprintf("Field type %s does not exist", $name));
        }

        // Type name deprecation error
        if ($name !== get_class($type)) {
            trigger_error(sprintf(
                "Using alias %s for field type %s is deprecated, please remove them and use FQCNs", $name, get_class($type)
            ), E_USER_DEPRECATED);
        }

        return $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getTableType($name)
    {
        if ($this->tableTypeLocator->has($name)) {
            $type = $this->tableTypeLocator->get($name);
        } elseif (class_exists($name)) {
            if (!in_array('Nours\TableBundle\Table\TableTypeInterface', class_implements($name))) {
                throw new \InvalidArgumentException(sprintf("Table type %s must implement FieldTypeInterface", $name));
            }
            $type = new $name;
        } else {
            throw new \InvalidArgumentException(sprintf("Table type %s does not exist", $name));
        }

        return $type;
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
        // Type extensions are already resolved
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
     * Find extnesions recursively
     *
     * @param $extensions
     * @param $name
     */
    private function findExtensions(&$extensions, $name)
    {
        $extension = $this->getExtension($name);

        // Put deps first
        foreach ((array)$extension->getDependency() as $dep) {
            if (!isset($extensions[$dep])) {
                $this->findExtensions($extensions, $dep);
            }
        }

        $extensions[$name] = $extension;
    }

    /**
     * {@inheritdoc}
     */
    public function normalizeTableOptions(array $options, array $fields)
    {
        foreach ($this->getExtensions() as $extension) {
            $options = $extension->normalizeTableOptions($options, $fields);
        }

        return $options;
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
        // Ensure extensions are loaded in order
        $this->sortedExtensions = array();
        foreach ($this->extensions as $extension) {
            $this->addExtensionToSorted($extension);
        }
    }


    private function addExtensionToSorted(ExtensionInterface $extension)
    {
        // Add deps first
        foreach ((array)$extension->getDependency() as $dep) {
            $this->addExtensionToSorted($this->getExtension($dep));
        }

        // Add this one
        $name = $extension->getName();
        if (isset($this->sortedExtensions[$name])) {
            return;
        }

        $this->sortedExtensions[$name] = $extension;
    }
}