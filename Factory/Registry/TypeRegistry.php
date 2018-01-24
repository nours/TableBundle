<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Factory\Registry;

use Psr\Container\ContainerInterface;

/**
 * A kind of type locator, to support legacy Symfony versions
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TypeRegistry implements ContainerInterface
{
    private $types;

    public function __construct(array $types)
    {
        $this->types = $types;
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        if (!isset($this->types[$id])) {
            throw new NotFoundException(sprintf(
                'Type %s not found'
            ));
        }

        return $this->types[$id];
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        return isset($this->types[$id]);
    }
}