<?php

namespace Nours\TableBundle\Builder;

use Nours\TableBundle\Factory\TableFactoryInterface;
use Nours\TableBundle\Table\Table;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
     * @param OptionsResolverInterface $resolver
     * @param array $options
     */
    public function __construct($name, TableFactoryInterface $factory, OptionsResolverInterface $resolver, array $options = array())
    {
        $this->name     = $name;
        $this->factory  = $factory;
        $this->resolver = $resolver;
        $this->options  = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function add($name, $type = null, array $options = array())
    {
        $this->fields[$name] = $this->factory->createField($name, $type, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * {@inheritdoc}
     */
    public function getTable()
    {
        $options = array_merge($this->options, array(
            'fields' => $this->getFields()
        ));
        $table = new Table($this->name, $this->fields, $this->resolver->resolve($options));
        
        return $table;
    }
}