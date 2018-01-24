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

use Nours\TableBundle\Table\TableTypeInterface;
use Psr\Container\ContainerInterface;

/**
 * Class ContainerTypeRegistry
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class ContainerTypeRegistry
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $name
     *
     * @return TableTypeInterface|null
     */
    public function getType($name)
    {
        return $this->container->has($name) ? $this->container->get($name) : null;
    }
}