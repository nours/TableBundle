<?php

namespace Nours\TableBundle\Twig\Extension;

use Nours\TableBundle\Renderer\TwigRendererInterface;
use Nours\TableBundle\Field\FieldInterface;
use Nours\TableBundle\Table\TableInterface;

/**
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TableExtension extends \Twig_Extension
{
	/**
	 * @var TwigRendererInterface
	 */
	private $renderer;
	
	/**
	 * @param TwigRendererInterface $renderer
	 */
	public function __construct(TwigRendererInterface $renderer)
	{
		$this->renderer = $renderer;
	}
	
	/**
     * {@inheritdoc}
	 */
	public function initRuntime(\Twig_Environment $environment)
	{
		$this->renderer->setEnvironment($environment);
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
            'render_table' => new \Twig_Function_Method($this, 'renderTable', array('is_safe' => array('html'))),
            'render_table_field' => new \Twig_Function_Method($this, 'renderField', array('is_safe' => array('html'))),
        );
    }
    
    /**
     * Renders a table part.
     *
     * @param TableInterface $table
     * @return string
     */
    public function renderTable(TableInterface $table, $part = null)
    {
    	return $this->renderer->renderTable($table, $part);
    }
    
    /**
     * Renders a field part.
     *
     * @param FieldInterface $field
     * @param string $part
     * @return string
     */
    public function renderField(FieldInterface $field, $part = null)
    {
    	return $this->renderer->renderField($field, $part);
    }
}