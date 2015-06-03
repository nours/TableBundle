<?php

namespace Nours\TableBundle\Renderer;

use Nours\TableBundle\Table\TableInterface;
use Nours\TableBundle\Field\FieldInterface;

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
     * @param TableInterface $table
     * @param string $part
     * @return string
     */
    public function renderTable(TableInterface $table, $part = null);

    /**
     * Renders a field part
     * 
     * @param FieldInterface $field
     * @param string $part
     * @return string
     */
    public function renderField(FieldInterface $field, $part = null);
}