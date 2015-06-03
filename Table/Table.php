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
     * @var string
     */
    private $name;

    /**
     * @var FieldInterface[]
     */
    private $fields;

    /**
     * @var array
     */
    private $options;

    /**
     * @var array
     */
    private $data;

    /**
     * @var integer
     */
    private $total;

    /**
     * @var integer
     */
    private $pages;

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
     * Sets the paginated data collection.
     *
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * Sets the total count of items.
     *
     * @param $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * Sets the page count.
     *
     * @param $pages
     */
    public function setPages($pages)
    {
        $this->pages = $pages;
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
        return $this->pages;
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
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