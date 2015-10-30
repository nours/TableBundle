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

/**
 * Interface for table renderers.
 * 
 * A renderer is able to render several blocks for one table, each one having to be available in themes.
 *
 * Fields as well can be implemented using different blocks in themes.
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
interface TableRendererInterface
{
    /**
     * Render a table part.
     *
     * @param View $tableView
     * @param string $part
     * @return string
     */
    public function renderTable(View $tableView, $part = null);

    /**
     * Renders a field part
     * 
     * @param View $fieldView
     * @param string $part
     * @return string
     */
    public function renderField(View $fieldView, $part = null);
}