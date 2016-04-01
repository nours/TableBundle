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
use Nours\TableBundle\Table\TableTypeInterface;
use Nours\TableBundle\Field\FieldTypeInterface;


/**
 * Holds table and field types.
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TypesRegistry implements TypesRegistryInterface
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
     * {@inheritdoc}
     */
    public function addTableType(TableTypeInterface $type)
    {
        $this->tableTypes[$type->getName()] = $type;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated
     */
    public function addFieldType(FieldTypeInterface $type)
    {
        $this->fieldTypes[$type->getName()] = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getTableType($name)
    {
        if (!isset($this->tableTypes[$name])) {
            $this->throwBadTableTypeException($name);
        }

        return $this->tableTypes[$name];
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