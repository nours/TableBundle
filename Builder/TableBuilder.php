<?php

namespace Nours\TableBundle\Builder;

use Nours\TableBundle\Factory\TableFactoryInterface;
use Nours\TableBundle\Table\ResolvedType;
use Nours\TableBundle\Table\Table;
use Nours\TableBundle\Table\TableInterface;

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
     * @param string $name
     * @param $type
     * @param array $options
     *
     * @return $this
     */
    public function add(string $name, $type = null, array $options = array()): TableBuilder
    {
        $this->fields[$name] = $this->factory->createField($name, $type, $options, $this->type->getExtensions());

        return $this;
    }

    /**
     * @return TableInterface
     */
    public function getTable(): TableInterface
    {
        return new Table(
            $this->type,
            $this->fields,
            $this->factory->normalizeTableOptions($this->options, $this->fields)
        );
    }
}