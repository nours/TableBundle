<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Field;

use Doctrine\Common\Inflector\Inflector;
use Nours\TableBundle\Table\TableInterface;

/**
 * Final representation/view for table fields.
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class Field implements FieldInterface
{
    /**
     * @var FieldTypeInterface
     */
    private $type;

    /**
     * @var string
     */
    private $name;

    /**
     * @var FieldTypeInterface[]
     */
    private $ancestors;

    /**
     * @var TableInterface
     */
    private $table;
    
    /**
     * @var array
     */
    private $options;

    /**
     *
     * @param string $name
     * @param FieldTypeInterface $type
     * @param array $options
     * @param FieldTypeInterface[] $ancestors
     */
    public function __construct($name, FieldTypeInterface $type, array $options, array $ancestors = array())
    {
        $this->name      = $name;
        $this->ancestors = $ancestors;
        $this->type      = $type;
        $this->options   = $options;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setTable(TableInterface $table)
    {
        $this->table = $table;
    }

    /**
     * {@inheritdoc}
     */
    public function getTable()
    {
        return $this->table;
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
    public function getParent()
    {
        return $this->ancestors ? reset($this->ancestors) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAncestors()
    {
        return $this->ancestors;
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeName()
    {
        return $this->type->getName();
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
        return $this->getOption('label');
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
     * @return mixed
     */
    public function getWidth()
    {
        return $this->options['width'];
    }

    /**
     * {@inheritdoc}
     */
    public function isDisplayed()
    {
        return $this->options['display'];
    }
}