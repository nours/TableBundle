<?php

namespace Nours\TableBundle\Tests\Renderer;

use Nours\TableBundle\Table\TableInterface;
use Nours\TableBundle\Tests\TestCase;
use Nours\TableBundle\Renderer\TwigRenderer;


/**
 * Tests for Twig renderer.
 *
 *
 * See the templates :
 *  - Tests/app/Resources/views/table.html.twig
 *  - Tests/app/Resources/views/fields.html.twig
 *
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TwigRendererTest extends TestCase
{
    /**
     * @var TableInterface
     */
    private $table;

    public function setUp()
    {
        parent::setUp();

        $this->table = $this->createTable('post');
    }

    /**
     * Render the main part of a table.
     *
     * See Tests/app/Resources/views/table.html.twig
     */
    public function testRenderTable()
    {
        $renderer = $this->getRenderer();

        $html = $renderer->renderTable($this->table);

        $this->assertEquals('<table></table>', $html);
    }

    /**
     * Render a javascript block of a table.
     */
    public function testRenderTableJavascript()
    {
        $renderer = $this->getRenderer();

        $html = $renderer->renderTable($this->table, 'javascript');

        $this->assertEquals('<script></script>', $html);
    }

    /**
     * Render a main part of a field
     */
    public function testRenderTextFieldMainBlock()
    {
        $renderer = $this->getRenderer();

        $html = $renderer->renderField($this->table->getField('content'));

        $this->assertEquals('field_text', $html);
    }

    /**
     * Render a secondary part of a field
     */
    public function testRenderTextFieldPart()
    {
        $renderer = $this->getRenderer();

        $html = $renderer->renderField($this->table->getField('content'), 'part');

        $this->assertEquals('field_text_part', $html);
    }

    /**
     * Render a main part of a field, using default blocks
     */
    public function testRenderDefaultField()
    {
        $renderer = $this->getRenderer();

        $field = $this->table->getField('active');

        $html = $renderer->renderField($field);

        $this->assertEquals('field', $html);
    }

    /**
     * Render a secondary part of a field, using default blocks
     */
    public function testRenderDefaultFieldPart()
    {
        $renderer = $this->getRenderer();

        $field = $this->table->getField('active');

        $html = $renderer->renderField($field, 'part');

        $this->assertEquals('field_part', $html);
    }

    /**
     * Render a secondary part of a field, using default blocks
     */
    public function testRenderTableThrowsIfBlockNotFound()
    {
        $this->setExpectedException('RuntimeException');

        $this->getRenderer()->renderTable($this->table, 'foo');
    }

    /**
     * Render a secondary part of a field, using default blocks
     */
    public function testRenderFieldThrowsIfBlockNotFound()
    {
        $this->setExpectedException('RuntimeException');

        $this->getRenderer()->renderField($this->table->getField('id'), 'bar');
    }

    /**
     * @return TwigRenderer
     */
    private function getRenderer()
    {
        // Need to load Twig environment first
        $this->get('twig')->initRuntime();

        return $this->get('nours_table.table_renderer.twig');
    }
}