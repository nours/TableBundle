<?php

namespace Nours\TableBundle\Table\Builder;

use Nours\TableBundle\Table\Factory\TableFactoryInterface;
use Nours\TableBundle\Table\ResolvedType;
use Nours\TableBundle\Table\Table;

class TableBuilder
{
    /**
     * @var ResolvedType
     */
    private $type;

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
     * @param ResolvedType $type
     * @param TableFactoryInterface $factory
     * @param array $options
     */
    public function __construct(ResolvedType $type, TableFactoryInterface $factory, array $options)
    {
        $this->type    = $type;
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
        $table = new Table($this->type, $this->fields, $this->options);
        
        return $table;
    }
}