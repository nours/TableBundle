<?php

namespace Nours\TableBundle\Builder;

use Nours\TableBundle\Factory\TableFactoryInterface;
use Nours\TableBundle\Table\Table;

class TableBuilder implements TableBuilderInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var TableFactory
     */
    private $factory;

    /**
     * @var array
     */
    private $options;

    public function __construct($name, TableFactoryInterface $factory, array $options = array())
    {
        $this->name    = $name;
        $this->factory = $factory;
        $this->options = $options;
    }

    
    /**
     * (non-PHPdoc)
     * @see \Nours\AdminBundle\Table\Builder\TableBuilder::add()
     */
    public function add($name, $type = null, array $options = array())
    {
        $this->fields[$name] = $this->factory->createField($name, $type, $options);
    }

    
    public function getTable()
    {
        $table = new Table($this->name, $this->fields, $this->options);
        
        return $table;
    }
}