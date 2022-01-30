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
use RuntimeException;
use Throwable;
use Twig\Environment;
use Twig\TemplateWrapper;

/**
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TwigRenderer implements TableRendererInterface
{
    /**
     * @var TemplateWrapper[]
     */
    private $templates;

    /**
     * @var string
     */
    private $defaultThemes;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * Cache for table themes
     *
     * @var array
     */
    private $themes = array();

    /**
     *
     * @param Environment $twig
     * @param array $defaultThemes
     */
    public function __construct(Environment $twig, array $defaultThemes)
    {
        $this->twig          = $twig;
        $this->defaultThemes = $defaultThemes;
    }

    private function getCacheKey(View $view): string
    {
        return $view->table->getType()->getCacheKey();
    }

    /**
     * Sets a theme for a table view
     *
     * @param View $view
     * @param $themes
     */
    public function setTheme(View $view, $themes)
    {
        $cacheKey = $this->getCacheKey($view);

        $this->themes[$cacheKey] = $themes;
    }

    /**
     * Loads the templates used by current theme.
     */
    private function loadTemplates(array $themes): array
    {
        $templates = array();
        foreach ($themes as $theme) {
            $key = is_object($theme) ? spl_object_hash($theme) : $theme;

            if (!isset($this->templates[$key])) {
                $template = $this->twig->load($theme);

                $this->templates[$key] = $template;
            }

            $templates[] = $this->templates[$key];
        }

        return $templates;
    }

    /**
     * @param string $blockName
     * @param array $themes
     *
     * @return TemplateWrapper|null
     */
    private function getTemplateForBlock(string $blockName, array $themes): ?TemplateWrapper
    {
        $templates = $this->loadTemplates($themes);

        foreach ($templates as $template) {
            if ($template->hasBlock($blockName)) {
                return $template;
            }
        }

        return null;
    }

    /**
     * @param array $blockNames
     * @param string $cacheKey
     * @param array $context
     *
     * @return string
     * @throws Throwable
     */
    private function renderBlock(array $blockNames, string $cacheKey, array $context): string
    {
        $template = $blockName = null;

        $themes = $this->defaultThemes;
        if (isset($this->themes[$cacheKey])) {
            $themes = array_merge($this->themes[$cacheKey], $this->defaultThemes);
        }

        // Search the template for first block available
        foreach ($blockNames as $name) {
            if ($template = $this->getTemplateForBlock($name, $themes)) {
                $blockName = $name;
                break;
            }
        }

        // Throw if no matching blocks are found
        if (empty($template)) {
            throw new RuntimeException(sprintf(
                "Block%s %s not found in table themes (%s)",
                count($blockNames) > 1 ? 's' : '', implode(', ', $blockNames), implode(', ', $this->defaultThemes)
            ));
        }

        return $template->renderBlock($blockName, $context);
    }
    
    /**
     * {@inheritdoc}
     */
    public function renderTable(View $tableView, string $part = null): string
    {
        $context = $tableView->vars;
        $context['table'] = $tableView;

        return $this->renderBlock(
            $this->getBlockPrefixes($tableView, $part),
            $this->getCacheKey($tableView),
            $context
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function renderField(View $fieldView, string $part = null): string
    {
        $context = $fieldView->vars;
        $context['table'] = $fieldView->parent;
        $context['field'] = $fieldView;

        return $this->renderBlock(
            $this->getBlockPrefixes($fieldView, $part),
            $this->getCacheKey($fieldView->parent),
            $context
        );
    }

    /**
     * @param View $view
     * @param string|null $part
     *
     * @return array
     */
    private function getBlockPrefixes(View $view, string $part = null): array
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