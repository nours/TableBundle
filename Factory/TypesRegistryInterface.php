<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Factory;

use Nours\TableBundle\Field\FieldTypeInterface;
use Nours\TableBundle\Table\TableTypeInterface;

/**
 * Holds table and field types.
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
interface TypesRegistryInterface
{
    /**
     * @param string $name
     *
     * @return TableTypeInterface|null
     */
    public function getTableType($name);

    /**
     * @param string $name
     *
     * @return FieldTypeInterface|null
     */
    public function getFieldType($name);
}