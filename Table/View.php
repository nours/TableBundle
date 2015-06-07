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
     * The var names for serialization
     *
     * @var array
     */
    public $serializedVars = array();

    /**
     * Options (not passed to templates not serialized)
     *
     * @var array
     */
    public $options = array();

    /**
     * The field views
     *
     * @var View[]
     */
    public $fields = array();

    /**
     * @var View
     */
    public $parent;

    /**
     * @param View $parent
     */
    public function __construct(View $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->fields);
    }
}