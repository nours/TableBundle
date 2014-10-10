<?php
namespace Nours\TableBundle\Twig\Table;

use Nours\TableBundle\Renderer\TableRenderer;

/**
 * Solves the Twig environment dependency injection
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
interface TwigRendererInterface extends TableRenderer
{
    /**
     * @param \Twig_Environment $environment
     */
    public function setEnvironment(\Twig_Environment $environment);
}