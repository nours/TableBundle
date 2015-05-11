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
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
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
    public function getPropertyPath()
    {
        return $this->getOption('property_path', Inflector::tableize($this->name));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return $this->getOption('label', $this->name);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getOption($name, $default = null)
    {
        return isset($this->options[$name]) ? $this->options[$name] : $default;
    }

    /**
     * {@inheritdoc}
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

    /**
     */
    public function isSearchable()
    {
        return $this->options['searchable'];
    }
}