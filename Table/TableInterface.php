<?php

namespace Nours\TableBundle\Table;

use Nours\TableBundle\Field\FieldInterface;

interface TableInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getFields();

    /**
     * @param string $name
     * @return FieldInterface
     */
    public function getField($name);

    /**
     * @return integer
     */
    public function getPage();

    /**
     * @return integer
     */
    public function getLimit();

    /**
     * @return integer
     */
    public function getPages();

    /**
     * @return integer
     */
    public function getTotal();

    /**
     * @return array
     */
    public function getData();

    /**
     * @return boolean
     */
    public function isSearchable();

    /**
     * @return boolean
     */
    public function isSortable();

    /**
     * @param $name
     * @param mixed $default
     * @return mixed
     */
    public function getOption($name, $default = null);

    /**
     * @param $data
     */
    public function setData(array $data);

    /**
     * @param $total
     */
    public function setTotal($total);

    /**
     * @param $pages
     */
    public function setPages($pages);
}