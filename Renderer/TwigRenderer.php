<?php

namespace Nours\TableBundle\Renderer;

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
     * @var string
     */
    private $templateNames;

    /**
     * 
     * @param array $templates
     */
    public function __construct(array $templates)
    {
        $this->templateNames = $templates;
    }


    private function loadTemplates()
    {
        if (!empty($this->templates)) {
            return;
        }

        $this->templates = array();
        foreach ($this->templateNames as $name) {
            $this->templates[] = $this->environment->loadTemplate($name);
        }
    }
    
    /**
     * @param \Twig_Environment $environment
     */
    public function setEnvironment(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @param $block
     * @return \Twig_Template
     */
    private function getTemplateForBlock($block)
    {
        $this->loadTemplates();

        foreach ($this->templates as $template) {
            if ($template->hasBlock($block)) {
                return $template;
            }
        }

        return null;
    }
    
    /**
     * {@inheritdoc}
     */
    public function renderTable(TableInterface $table, $part = null)
    {
        $blockName = 'table';
        if (!empty($part)) {
            $blockName .= '_' . $part;
        }

        $template = $this->getTemplateForBlock($blockName);

        $this->assertTemplateFound($template, $blockName);

        $context = array(
            'table' => $table
        );

        return $template->renderBlock($blockName, $context);
    }
    
    /**
     * {@inheritdoc}
     */
    public function renderField(FieldInterface $field, $part = null)
    {
        $suffix = $part ? '_' . $part : '';

        $blocks = array(
            'field_' . $field->getType() . $suffix,
            'field' . $suffix
        );

        // Search template for first block available
        $template = null;
        $block    = null;
        foreach ($blocks as $blockName) {
            if ($found = $this->getTemplateForBlock($blockName)) {
                $template = $found;
                $block    = $blockName;
                break;
            }
        }

        $this->assertTemplateFound($template, $blocks);

        $context = $field->getOptions();
        $context = array_merge($context, array(
            'field' => $field,
            'table' => $field->getTable(),
            'name'  => $field->getName(),
            'label' => $field->getLabel()
        ));
        
        return $template->renderBlock($block, $context);
    }

    /**
     * @param $template
     * @param string|array $blocks
     */
    private function assertTemplateFound($template, $blocks)
    {
        if (empty($template)) {
            $blocks = (array)$blocks;

            throw new \RuntimeException(sprintf(
                "Block%s %s not found in table themes (%s)",
                count($blocks) > 1 ? 's' : '', implode(', ', $blocks), implode(', ', $this->templateNames)
            ));
        }
    }
}