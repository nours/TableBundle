<?php

namespace Nours\TableBundle\Field;
use Nours\TableBundle\Table\TableInterface;

/**
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
interface FieldInterface
{
    /**
     * The type name of the field
     *
     * @return string
     */
    public function getType();

    /**
     * The name of the field
     *
     * @return string
     */
    public function getName();

    /**
     * Property path
     *
     * @return string
     */
    public function getPropertyPath();

    /**
     * The label
     *
     * @return string
     */
    public function getLabel();

    /**
     * @param $name
     * @param null $default
     * @return mixed
     */
    public function getOption($name, $default = null);

    /**
     * @return array
     */
    public function getOptions();

    /**
     * If this field is sortable
     *
     * @return bool
     */
    public function isSortable();

    /**
     * If this field is searchable
     *
     * @return bool
     */
    public function isSearchable();

    /**
     * This field's table
     *
     * @return TableInterface
     */
    public function getTable();

    /**
     * Sets the table
     *
     * @param TableInterface $table
     */
    public function setTable(TableInterface $table);
}