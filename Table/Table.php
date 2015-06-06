<?php

namespace Nours\TableBundle\Table;

use JMS\Serializer\Annotation as Serializer;
use Nours\TableBundle\Field\FieldInterface;

/**
 * A table instance.
 * 
 * @Serializer\ExclusionPolicy("all")
 *
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class Table implements TableInterface
{
    /**
     * @var ResolvedType
     */
    private $type;

    /**
     * @var FieldInterface[]
     */
    private $fields;

    /**
     * @var array
     */
    private $options;

    /**
     * 
     * @param ResolvedType $type
     * @param FieldInterface[] $fields
     * @param array $options
     */
    public function __construct(ResolvedType $type,  array $fields, array $options)
    {
        $this->type       = $type;
        $this->fields     = $fields;
        $this->options    = $options;

        foreach ($fields as $field) {
            $field->setTable($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->type->getName();
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
    public function getField($name)
    {
        if (!isset($this->fields[$name])) {
            throw new \InvalidArgumentException(sprintf(
                "Table type %s has no field named %s (but %s)",
                $this->getName(), $name, implode(', ', array_keys($this->fields))
            ));
        }

        return $this->fields[$name];
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * {@inheritdoc}
     */
    public function getPage()
    {
        return $this->getOption('page');
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return integer
     */
    public function getLimit()
    {
        return $this->getOption('limit');
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return integer
     */
    public function getPages()
    {
        return $this->getOption('pages');
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->getOption('total');
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return array
     */
    public function getData()
    {
        return $this->getOption('data');
    }

    /**
     * 
     * @return string
     */
    public function getUrl()
    {
        return $this->getOption('url');
    }

    /**
     * @return boolean
     */
    public function isSearchable()
    {
        foreach ($this->fields as $field) {
            if ($field->isSearchable()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return boolean
     */
    public function isSortable()
    {
        foreach ($this->fields as $field) {
            if ($field->isSortable()) {
                return true;
            }
        }

        return false;
    }


//    public function hasRowStyle()
//    {
//        return (bool)$this->getOption('row_style');
//    }

    /**
     * @return mixed
     */
    public function getOption($name, $default = null)
    {
        return isset($this->options[$name]) ? $this->options[$name] : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function createView()
    {
        return $this->type->createView($this);
    }
}