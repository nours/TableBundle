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
     * @return array
     */
    public function hasRowStyle();
}