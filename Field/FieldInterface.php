<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Field;

use Nours\TableBundle\Table\TableInterface;

/**
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
interface FieldInterface
{
    /**
     * The resolved type of the field
     *
     * @return FieldTypeInterface
     */
    public function getType();

    /**
     * The resolved type of the field
     *
     * @return string
     */
    public function getTypeName();

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
     * @deprecated
     */
    public function isSortable();

    /**
     * If this field is searchable
     *
     * @return bool
     * @deprecated
     */
    public function isSearchable();

    /**
     * @return bool
     */
    public function isDisplayed();

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