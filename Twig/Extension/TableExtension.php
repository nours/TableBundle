<?php

namespace Nours\TableBundle\Twig\Extension;

use Nours\TableBundle\Renderer\TableRendererInterface;
use Nours\TableBundle\Table\View;

/**
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TableExtension extends \Twig_Extension
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
            'render_table' => new \Twig_Function_Method($this, 'renderTable', array('is_safe' => array('html'))),
            'render_table_field' => new \Twig_Function_Method($this, 'renderField', array('is_safe' => array('html'))),
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