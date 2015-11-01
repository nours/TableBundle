<?php

namespace Nours\TableBundle\Table;

use JMS\Serializer\Annotation as Serializer;
use Nours\TableBundle\Field\FieldInterface;
use Symfony\Component\HttpFoundation\Request;

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
     * @var bool Flag
     */
    private $handled = false;

    /**
     * 
     * @param ResolvedType $type
     * @param FieldInterface[] $fields
     * @param array $options
     */
    public function __construct(ResolvedType $type, array $fields, array $options)
    {
        $this->type       = $type;
        $this->fields     = $fields;
        $this->options    = $options;

        foreach ($fields as $field) {
            $field->setTable($this);
        }
    }

    /**
     * @return ResolvedType
     */
    public function getType()
    {
        return $this->type;
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
     * {@inheritdoc}
     */
    public function getPage()
    {
        return $this->getOption('page');
    }

    /**
     * @return integer
     */
    public function getLimit()
    {
        return $this->getOption('limit');
    }

    /**
     * @return integer
     */
    public function getPages()
    {
        return $this->getOption('pages');
    }

    /**
     * @return integer
     */
    public function getTotal()
    {
        return $this->getOption('total');
    }

    /**
     * @return array
     */
    public function getData()
    {
        $data     = $this->getOption('data');

        /** @var \Closure $callback */
        $callback = $this->getOption('data_callback');

        if (empty($data) && $callback) {
            $data = $callback();
            $this->setOption('data', $data);
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function hasData()
    {
        return $this->getOption('data_callback') || $this->getOption('data');
    }

    /**
     * @param mixed $page
     */
    public function setPage($page)
    {
        $this->setOption('page', $page);
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit)
    {
        $this->setOption('limit', $limit);
    }

    /**
     * @param mixed $pages
     */
    public function setPages($pages)
    {
        $this->setOption('pages', $pages);
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total)
    {
        $this->setOption('total', $total);
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->setOption('data', $data);
    }

    /**
     * Sets the data lazy loader
     *
     * @param \Closure $callback
     */
    public function setDataCallback($callback)
    {
        $this->setOption('data_callback', $callback);
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
     * {@inheritdoc}
     */
    public function getOption($name, $default = null)
    {
        return isset($this->options[$name]) ? $this->options[$name] : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function createView()
    {
        // A table need to be handled before creating view
        return $this->handle()->type->createView($this);
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request = null)
    {
        if (false === $this->handled) {
            $this->type->handle($this, $request);
            $this->handled = true;
        }
        return $this;
    }
}