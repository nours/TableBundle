<?php

namespace Nours\TableBundle\Twig\Extension;

use Nours\TableBundle\Twig\Table\TwigRendererInterface;
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
            'table_html' => new \Twig_Function_Method($this, 'renderTableHtml', array('is_safe' => array('html'))),
            'table_javascript' => new \Twig_Function_Method($this, 'renderTableJavascript', array('is_safe' => array('html'))),
            'table_field' => new \Twig_Function_Method($this, 'renderField', array('is_safe' => array('html'))),
        );
    }
    
    /**
     * Réalise le rendu d'une grid.
     *
     * @param TableInterface $table
     * @return string
     */
    public function renderTableHtml(TableInterface $table)
    {
    	return $this->renderer->renderTable($table);
    }
    
    /**
     *
     * @param TableInterface $table
     * @return string
     */
    public function renderTableJavascript(TableInterface $table)
    {
    	return $this->renderer->renderTableJavascript($table);
    }
    
    /**
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