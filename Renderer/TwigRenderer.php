<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Renderer;

use Nours\TableBundle\Table\View;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TwigRenderer implements TableRendererInterface
{
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
    private $container;

    /**
     *
     * @param ContainerInterface $container
     * @param array $templates
     */
    public function __construct(ContainerInterface $container, array $templates)
    {
        $this->container     = $container;
        $this->templateNames = $templates;
    }

    /**
     * Loads the templates used by current theme.
     */
    private function loadTemplates()
    {
        if (!empty($this->templates)) {
            return;
        }

        // Avoid dependencies issues with the table extension
        $twig = $this->container->get('twig');

        $this->templates = array();
        foreach ($this->templateNames as $name) {
            $this->templates[] = $twig->loadTemplate($name);
        }
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
        // Default prefixes (@see CoreExtension)
        $blockNames = $view->vars['block_prefixes'];

        // If specific part is asked, append it to block names
        if ($part) {
            $blockNames = array_map(function($blockName) use ($part) {
                return $blockName . '_' . $part;
            }, $blockNames);
        }

        return $blockNames;
    }
}