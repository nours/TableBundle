<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Table;


/**
 * Class View
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class View implements \IteratorAggregate
{
    /**
     * The template vars
     *
     * @var array
     */
    public $vars = array();

    /**
     * The field views
     *
     * @var View[]
     */
    public $fields = array();

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->fields);
    }
}