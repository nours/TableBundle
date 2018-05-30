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
     * @var View
     */
    public $parent;

    /**
     * @var TableInterface
     */
    public $table;

    /**
     * @param View $parent
     */
    public function __construct(View $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->table->getData();
    }

    /**
     * @return mixed
     */
    public function toJson()
    {
        $json = $this->table->toJson();

        foreach ($this->table->getOption('serialized_vars') as $var) {
            if (isset($this->vars[$var])) {
                $json[$var] = $this->vars[$var];
            }
        }

        return $json;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->fields);
    }
}