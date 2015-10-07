<?php

namespace Nours\TableBundle\Table;

use Nours\TableBundle\Field\FieldInterface;
use Symfony\Component\HttpFoundation\Request;

interface TableInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return ResolvedType
     */
    public function getType();

    /**
     * @return FieldInterface[]
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
     * @param integer $page
     */
    public function setPage($page);

    /**
     * @param integer $limit
     */
    public function setLimit($limit);

    /**
     * @param integer $pages
     */
    public function setPages($pages);

    /**
     * @param integer $total
     */
    public function setTotal($total);

    /**
     * @param mixed $data
     */
    public function setData($data);

    /**
     * @param \Closure $callback
     */
    public function setDataCallback($callback);

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @param $name
     * @param mixed $default
     * @return mixed
     */
    public function getOption($name, $default = null);

    /**
     * @param $name
     * @param $value
     */
    public function setOption($name, $value);

    /**
     * @return View
     */
    public function createView();

    /**
     * @param Request $request
     * @return TableInterface
     */
    public function handle(Request $request = null);
}