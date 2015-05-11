<?php

namespace Nours\TableBundle\Table\Builder;

use Nours\TableBundle\Factory\TableFactoryInterface;
use Nours\TableBundle\Table\Table;

class TableBuilder
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var TableFactoryInterface
     */
    private $factory;

    /**
     * @var array
     */
    private $options;

    /**
     * @var array
     */
    private $fields = array();

    /**
     * @param $name
     * @param TableFactoryInterface $factory
     * @param array $options
     */
    public function __construct($name, TableFactoryInterface $factory, array $options)
    {
        $this->name    = $name;
        $this->factory = $factory;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function add($name, $type = null, array $options = array())
    {
        $this->fields[$name] = $this->factory->createField($name, $type, $options);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTable()
    {
        $table = new Table($this->name, $this->fields, $this->options);
        
        return $table;
    }
}