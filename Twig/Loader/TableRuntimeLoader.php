<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Twig\Loader;

use Nours\TableBundle\Renderer\TwigRenderer;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class TableRuntimeLoader
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TableRuntimeLoader implements \Twig_RuntimeLoaderInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * TableRuntimeExtension constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Creates the runtime implementation of a Twig element (filter/function/test).
     *
     * @param string $class A runtime class
     *
     * @return object|null The runtime instance or null if the loader does not know how to create the runtime for this class
     */
    public function load($class)
    {
        if (TwigRenderer::class == $class) {
            return $this->container->get('nours_table.table_renderer.twig');
        }
    }
}