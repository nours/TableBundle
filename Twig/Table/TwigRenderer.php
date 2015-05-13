<?php
namespace Nours\TableBundle\Twig\Table;

use Nours\TableBundle\Table\TableInterface;
use Nours\TableBundle\Field\FieldInterface;

/**
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TwigRenderer implements TwigRendererInterface
{
    /**
     * @var \Twig_Environment
     */
    private $environment;
    
    /**
     * @var \Twig_Template
     */
    private $template;

    /**
     * @var string
     */
    private $templateName;

    /**
     * 
     * @param string $templateName
     */
    public function __construct($templateName)
    {
        $this->templateName = $templateName;
    }
    
    /**
     * @param \Twig_Environment $environment
     */
    public function setEnvironment(\Twig_Environment $environment)
    {
        $this->environment = $environment;
        $this->template = $environment->loadTemplate($this->templateName);
    }

    /**
     * @param $block
     * @return mixed
     */
    private function getTemplateForBlock($block)
    {
        if ($this->template->hasBlock($block)) {
            return $this->template;
        }

        throw new \RuntimeException("Block $block was not found in template $this->templateName");
    }
    
    /**
     * {@inheritdoc}
     */
    public function renderTableJavascript(TableInterface $table)
    {
        $template = $this->getTemplateForBlock('table_javascript');

        $context = array(
            'table' => $table
        );
        
        return $template->renderBlock('table_javascript', $context);
    }
    
    /**
     * {@inheritdoc}
     */
    public function renderTable(TableInterface $table)
    {
        $template = $this->getTemplateForBlock('table_html');

        $context = array(
            'table' => $table
        );

        return $template->renderBlock('table_html', $context);
    }
    
    /**
     * {@inheritdoc}
     */
    public function renderField(FieldInterface $field, $part = null)
    {
        $blockName = 'field_' . $field->getType();

        if ($part) {
            $blockName .= '_' . $part;
        }

        $template = $this->getTemplateForBlock($blockName);

        $context = $field->getOptions();
        $context = array_merge($context, array(
            'field' => $field,
            'name' => $field->getName(),
            'label' => $field->getLabel()
        ));
        
        return $template->renderBlock($blockName, $context);
    }
}