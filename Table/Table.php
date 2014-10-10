<?php

namespace Nours\TableBundle\Table;

use JMS\Serializer\Annotation as Serializer;

/**
 * A table instance.
 * 
 * @Serializer\ExclusionPolicy("all")
 * @Serializer\XmlRoot("table")
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class Table implements TableInterface
{
    private $name;
    private $fields;
    private $options;
    
    /**
     * 
     * @param string $name
     * @param array $fields
     * @param array $options
     */
    public function __construct($name, array $fields, array $options)
    {
        $this->name       = $name;
        $this->fields     = $fields;
        $this->options    = $options;
    }

    /**
     * (non-PHPdoc)
     * @see \Nours\AdminBundle\Table\TableInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Nours\AdminBundle\Table\TableInterface::getFields()
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\XmlAttribute()
     * 
     * (non-PHPdoc)
     * @see \Nours\AdminBundle\Table\TableInterface::getPage()
     */
    public function getPage()
    {
        return $this->getOption('page');
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\XmlAttribute()
     * 
     * @return integer
     */
    public function getLimit()
    {
        return $this->getOption('limit');
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\XmlAttribute()
     * 
     * @return integer
     */
    public function getPages()
    {
        return $this->getOption('pages');
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\XmlAttribute()
     * 
     * @return integer
     */
    public function getTotal()
    {
        return $this->getOption('total');
    }

    /**
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


    public function hasRowStyle()
    {
        return (bool)$this->getOption('row_style');
    }

    /**
     * @return mixed
     */
    private function getOption($name)
    {
        return isset($this->options[$name]) ? $this->options[$name] : null;
    }
}