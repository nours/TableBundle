<?php

namespace Nours\TableBundle\Twig\Extension;

use Nours\TableBundle\Renderer\TableRendererInterface;
use Nours\TableBundle\Table\View;

/**
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TableExtension extends \Twig_Extension implements \Twig_Extension_InitRuntimeInterface
{
	/**
	 * @var TableRendererInterface
	 */
	private $renderer;
	
	/**
	 * @param TableRendererInterface $renderer
	 */
	public function __construct(TableRendererInterface $renderer)
	{
		$this->renderer = $renderer;
	}
	
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
            new \Twig_SimpleFunction('render_table', array($this, 'renderTable'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('render_table_field', array($this, 'renderField'), array('is_safe' => array('html'))),
        );
    }
    
    /**
     * Renders a table part.
     *
     * @param View $tableView
     * @return string
     */
    public function renderTable(View $tableView, $part = null)
    {
    	return $this->renderer->renderTable($tableView, $part);
    }
    
    /**
     * Renders a field part.
     *
     * @param View $fieldView
     * @param string $part
     * @return string
     */
    public function renderField(View $fieldView, $part = null)
    {
    	return $this->renderer->renderField($fieldView, $part);
    }
}