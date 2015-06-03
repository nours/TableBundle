<?php

namespace Nours\TableBundle\Renderer;

/**
 * Solves the Twig environment dependency injection
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
interface TwigRendererInterface extends TableRendererInterface
{
    /**
     * @param \Twig_Environment $environment
     */
    public function setEnvironment(\Twig_Environment $environment);
}