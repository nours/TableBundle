<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Twig\Node;

/**
 * Class TableThemeNode
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TableThemeNode extends \Twig_Node
{
    public function __construct(\Twig_Node $table, \Twig_Node $resources, $lineno, $tag = null)
    {
        parent::__construct(array('table' => $table, 'resources' => $resources), array(), $lineno, $tag);
    }

    /**
     * Compiles the node to PHP.
     *
     * @param \Twig_Compiler $compiler A Twig_Compiler instance
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write('$this->env->getRuntime(\'Nours\TableBundle\Renderer\TwigRenderer\')->setTheme(')
            ->subcompile($this->getNode('table'))
            ->raw(', ')
            ->subcompile($this->getNode('resources'))
            ->raw(");\n");
    }
}