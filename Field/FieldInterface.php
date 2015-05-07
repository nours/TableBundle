<?php

namespace Nours\TableBundle\Field;

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
     * todo: refactor ?
     *
     * @return string
     */
    public function getPath();

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
}