<?php

namespace Nours\TableBundle\Field;
use Doctrine\Common\Inflector\Inflector;

/**
 * Final representation/view for table fields.
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class Field implements FieldInterface
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $name;
    
    /**
     * @var array
     */
    private $options;
    
    /**
     * 
     * @param string $type
     * @param array $options
     */
    public function __construct($name, $type, array $options)
    {
        $this->name    = $name;
        $this->type    = $type;
        $this->options = $options;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Nours\TableBundle\Field\FieldInterface::getType()
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * (non-PHPdoc)
     * @see \Nours\TableBundle\Field\FieldInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * (non-PHPdoc)
     * @see \Nours\TableBundle\Field\FieldInterface::getPath()
     */
    public function getPath()
    {
        return Inflector::tableize($this->name);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Nours\TableBundle\Field\FieldInterface::getLabel()
     */
    public function getLabel()
    {
        return $this->options['label'] ?: $this->name;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Nours\TableBundle\Field\FieldInterface::getOptions()
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     */
    public function getWidth()
    {
        return $this->options['width'];
    }

    /**
     */
    public function isSortable()
    {
        return $this->options['sortable'];
    }
}