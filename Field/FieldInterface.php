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

/**
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
interface FieldInterface
{
    /**
     * The resolved type of the field
     *
     * @return FieldTypeInterface
     */
    public function getType(): FieldTypeInterface;

    /**
     * The name of the field
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Parent field element
     *
     * @return FieldTypeInterface|null
     */
    public function getParent(): ?FieldTypeInterface;

    /**
     * Field hierarchy
     *
     * @return FieldTypeInterface[]
     */
    public function getAncestors(): array;

    /**
     * Property path
     *
     * @return string
     */
    public function getPropertyPath(): string;

    /**
     * The label
     *
     * @return string
     */
    public function getLabel(): string;

    /**
     * @param $name
     * @param null $default
     * @return mixed
     */
    public function getOption($name, $default = null);

    /**
     * @return array
     */
    public function getOptions(): array;

    /**
     * @return bool
     */
    public function isDisplayed(): bool;

    /**
     * This field's table
     *
     * @return TableInterface
     */
    public function getTable(): TableInterface;

    /**
     * Sets the table
     *
     * @param TableInterface $table
     */
    public function setTable(TableInterface $table);
}