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
     * @param ExtensionInterface $extension
     */
    public function addTableExtension(ExtensionInterface $extension);

    /**
     * Creates a new table.
     * 
     * @param string|TableTypeInterface $type
     * @param array $options
     *
     * @return TableInterface
     */
    public function createTable($type, array $options = array()): TableInterface;

    /**
     * Creates a new field.
     *
     * @param string $name
     * @param string|FieldTypeInterface $type
     * @param array $options
     * @param array $extensions
     *
     * @return FieldInterface
     */
    public function createField(string $name, $type, array $options = array(), array $extensions = array()): FieldInterface;

    /**
     * @param $name
     *
     * @return FieldTypeInterface
     */
    public function getFieldType($name): FieldTypeInterface;

    /**
     * @return ExtensionInterface[]
     */
    public function getExtensions(): array;

    /**
     * @param TableTypeInterface $type
     * @return ExtensionInterface[]
     */
    public function getExtensionsForType(TableTypeInterface $type): array;

    /**
     * Normalize table options after collecting fields.
     *
     * @param array $options
     * @param FieldInterface[] $fields
     * @return array
     */
    public function normalizeTableOptions(array $options, array $fields): array;
}