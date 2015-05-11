<?php

namespace Nours\TableBundle\Table;

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