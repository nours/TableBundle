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
    private $name;

    /**
     * @var FieldInterface[]
     */
    private $fields;
    private $options;
    
    /**
     * 
     * @param string $name
     * @param FieldInterface[] $fields
     * @param array $options
     */
    public function __construct($name, array $fields, array $options)
    {
        $this->name       = $name;
        $this->fields     = $fields;
        $this->options    = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getFields()
    {
        return $this->fields;
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
}