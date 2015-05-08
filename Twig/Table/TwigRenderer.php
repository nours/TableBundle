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
     * @var \Twig_Template[]
     */
    private $templates;
    
    /**
     * 
     * @param array $templates
     */
    public function __construct(array $templates)
    {
        $this->templates = $templates;
    }
    
    /**
     * @param \Twig_Environment $environment
     */
    public function setEnvironment(\Twig_Environment $environment)
    {
        $this->environment = $environment;
        foreach ($this->templates as $key => $template) {
            $this->templates[$key] = $environment->loadTemplate($template);
        }
    }

    /**
     * @return \Twig_Template
     */
    private function getTemplateForBlock($block)
    {
        foreach ($this->templates as $template) {
            if ($template->hasBlock($block)) {
                return $template;
            }
        }

        throw new \RuntimeException("Block $block was not found in templates");
    }
    
    /**
     * {@inheritdoc}
     */
    public function renderJavascript(TableInterface $table)
    {
        $template = $this->getTemplateForBlock('table_javascript');

        $context = array(
            'table' => $table,
            'row_style' => null
        );

//        if ($table->hasRowStyle()) {
//            $block = $table->getName() . '_row_style';
//            $context['row_style'] = $this->getTemplateForBlock($block)->renderBlock($block, array());
//        }
        
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
    public function renderField(FieldInterface $field)
    {
        $blockName = 'field_' . $field->getType();

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