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
     * @deprecated Should always return null
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
     * Parent field element
     *
     * @return FieldTypeInterface|null
     */
    public function getParent();

    /**
     * Field hierarchy
     *
     * @return FieldTypeInterface[]
     */
    public function getAncestors();

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