<?php

namespace Nours\TableBundle\Renderer;

use Nours\TableBundle\Table\View;

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
     * @var array
     */
    private $cacheTemplates = array();

    /**
     * @var array
     */
    private $cacheBlockNames = array();

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
     * @param string $blockName
     * @return \Twig_Template
     */
    private function getTemplateForBlock($blockName)
    {
        $this->loadTemplates();

        foreach ($this->templates as $template) {
            if ($template->hasBlock($blockName)) {
                return $template;
            }
        }

        return null;
    }

    /**
     * @param $cacheKey
     * @param array $blockNames
     * @param array $context
     * @return string
     */
    private function renderBlock($cacheKey, array $blockNames, array $context)
    {
        $template  = null;
        $blockName = null;
        if (isset($this->cacheTemplates[$cacheKey])) {
            $template = $this->cacheTemplates[$cacheKey];
            $blockName = $this->cacheBlockNames[$cacheKey];
        }

        if (!isset($template)) {
            // Search the template for first block available
            foreach ($blockNames as $name) {
                if ($found = $this->getTemplateForBlock($name)) {
                    $template  = $found;
                    $blockName = $name;
                    break;
                }
            }

            // Throw if no matching blocks are found
            if (empty($template)) {
                throw new \RuntimeException(sprintf(
                    "Block%s %s not found in table themes (%s)",
                    count($blockNames) > 1 ? 's' : '', implode(', ', $blockNames), implode(', ', $this->templateNames)
                ));
            }
        }

        return $template->renderBlock($blockName, $context);
    }
    
    /**
     * {@inheritdoc}
     */
    public function renderTable(View $tableView, $part = null)
    {
        $context = $tableView->vars;
        $context['table'] = $tableView;

        return $this->renderBlock($tableView->vars['cache_key'], $this->getBlockPrefixes($tableView, $part), $context);
    }
    
    /**
     * {@inheritdoc}
     */
    public function renderField(View $fieldView, $part = null)
    {
        $context = $fieldView->vars;
        $context['table'] = $fieldView->parent;
        $context['field'] = $fieldView;

        return $this->renderBlock($fieldView->vars['cache_key'], $this->getBlockPrefixes($fieldView, $part), $context);
    }

    /**
     * @param View $view
     * @param string $part
     * @return array
     */
    private function getBlockPrefixes(View $view, $part = null)
    {
        $blockNames = $view->vars['block_prefixes'];
        if ($part) {
            $blockNames = array_map(function($blockName) use ($part) {
                return $blockName . '_' . $part;
            }, $blockNames);
        }

        return $blockNames;
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