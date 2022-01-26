<?php

namespace Nours\TableBundle\Twig\Extension;

use Nours\TableBundle\Twig\TokenParser\TableThemeTokenParser;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TableExtension extends AbstractExtension
{
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
            new TwigFunction('render_table', array('Nours\TableBundle\Renderer\TwigRenderer', 'renderTable'), array('is_safe' => array('html'))),
            new TwigFunction('render_table_field', array('Nours\TableBundle\Renderer\TwigRenderer', 'renderField'), array('is_safe' => array('html'))),
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