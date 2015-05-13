<?php

namespace Nours\TableBundle\Renderer;

use Nours\TableBundle\Table\TableInterface;
use Nours\TableBundle\Field\FieldInterface;

/**
 * Interface for table renderers.
 * 
 * A table is rendered with html and javascript parts.
 *
 * Fields can be rendered as multiple parts.
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
interface TableRenderer
{
    /**
     * Render the table.
     *
     * @param TableInterface $table
     * @return string
     */
    public function renderTable(TableInterface $table);

    /**
     * Render the javascript part of the table.
     * 
     * @param TableInterface $table
     * @return string
     */
    public function renderTableJavascript(TableInterface $table);

    /**
     * Renders a field part
     * 
     * @param FieldInterface $field
     * @param string $part
     * @return string
     */
    public function renderField(FieldInterface $field, $part = null);
}