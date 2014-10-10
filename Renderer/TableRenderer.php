<?php

namespace Nours\TableBundle\Renderer;

use Nours\TableBundle\Table\TableInterface;
use Nours\TableBundle\Field\FieldInterface;

/**
 * Interface for table renderers.
 * 
 * It supports two parts for the table : javascript and html. This is sufficient to integrate
 * most of the table design librairies.
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
interface TableRenderer
{
    /**
     * Renders the javascript part of the table.
     * 
     * @param TableInterface $table
     */
    public function renderJavascript(TableInterface $table);
    
    /**
     * Renders the javascript part of the table.
     * 
     * @param TableInterface $table
     */
    public function renderTable(TableInterface $table);
    
    /**
     * Renders a field part
     * 
     * @param FieldInterface $field
     */
    public function renderField(FieldInterface $field);
}