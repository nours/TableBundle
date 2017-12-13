<?php

namespace Nours\TableBundle\Twig\Extension;

use Nours\TableBundle\Renderer\TableRendererInterface;
use Nours\TableBundle\Table\View;
use Nours\TableBundle\Twig\TokenParser\TableThemeTokenParser;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TableExtension extends \Twig_Extension implements \Twig_Extension_InitRuntimeInterface
{
    /**
     * {@inheritdoc}
	 */
	public function initRuntime(\Twig_Environment $environment)
	{
	}

	/**
     * {@inheritdoc}
	 */
	public function getName()
	{
		return 'nours_table';
	}
	
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('render_table', array('Nours\TableBundle\Renderer\TwigRenderer', 'renderTable'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('render_table_field', array('Nours\TableBundle\Renderer\TwigRenderer', 'renderField'), array('is_safe' => array('html'))),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return array(
            new TableThemeTokenParser()
        );
    }
}