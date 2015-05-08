<?php

namespace Nours\TableBundle\Table\Builder;

use Nours\TableBundle\Factory\TableFactoryInterface;
use Nours\TableBundle\Table\Table;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableBuilder implements TableBuilderInterface
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
    private $resolver;

    /**
     * @var array
     */
    private $fields;

    /**
     * @param $name
     * @param TableFactoryInterface $factory
     * @param OptionsResolver $resolver
     */
    public function __construct($name, TableFactoryInterface $factory, OptionsResolver $resolver)
    {
        $this->name     = $name;
        $this->factory  = $factory;
        $this->resolver = $resolver;
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
    public function getTable(array $options)
    {
        $options['fields'] = $this->fields;

        $table = new Table($this->name, $this->fields, $this->resolver->resolve($options));
        
        return $table;
    }
}