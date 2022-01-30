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

use Nours\TableBundle\Table\TableInterface;
use Nours\TableBundle\Util\Inflector;

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
    public function __construct(string $name, FieldTypeInterface $type, array $options, array $ancestors = array())
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
    public function getTable(): TableInterface
    {
        return $this->table;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): FieldTypeInterface
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?FieldTypeInterface
    {
        return $this->ancestors ? reset($this->ancestors) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAncestors(): array
    {
        return $this->ancestors;
    }

    /**
     * {@inheritdoc}
     */
    public function getPropertyPath(): string
    {
        return $this->getOption('property_path', Inflector::tableize($this->name));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getLabel(): string
    {
        return $this->getOption('label');
    }
    
    /**
     * {@inheritdoc}
     */
    public function getOption($name, $default = null)
    {
        return $this->options[$name] ?? $default;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
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
    public function isDisplayed(): bool
    {
        return $this->options['display'];
    }
}