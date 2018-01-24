<?php

namespace Nours\TableBundle\Tests\Renderer;

use Nours\TableBundle\Table\TableInterface;
use Nours\TableBundle\Table\View;
use Nours\TableBundle\Tests\FixtureBundle\Table\FQCNTableType;
use Nours\TableBundle\Tests\FixtureBundle\Table\PostCommentsType;
use Nours\TableBundle\Tests\FixtureBundle\Table\PostEmbedType;
use Nours\TableBundle\Tests\FixtureBundle\Table\PostType;
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

    /**
     * @var View
     */
    private $view;

    public function setUp()
    {
        parent::setUp();

        $this->table = $this->createTable(PostType::class);
        $this->view = $this->table->createView();
    }

    /**
     * Render a FQCN loaded table
     */
    public function testRenderFQCNTable()
    {
        $table = $this->createTable(FQCNTableType::class);
        $view = $table->createView();

        $renderer = $this->getRenderer();

        $html = $renderer->renderTable($view);

        $this->assertEquals("FQCNTableType\nFQCNFieldType", $html);
    }

    /**
     * Render the main part of a table.
     *
     * See Tests/app/Resources/views/table.html.twig
     */
    public function testRenderPostTable()
    {
        $renderer = $this->getRenderer();
        $table = $this->createTable(PostType::class, array(
            'limit' => 10,
        ));

        $html = $renderer->renderTable($table->createView());

        $expected = <<<EOS
page=1
limit=10
pages=1
total=3
field=id
property_path=id
field=status
property_path=status
field=isActive
property_path=isActive
field=content
property_path=content
field=prototype
property_path=prototype

EOS;

        $this->assertEquals($expected, $html);
    }

    /**
     * Render the main part of a table.
     *
     * See Tests/app/Resources/views/table.html.twig
     */
    public function testRenderPostEmbedTable()
    {
        $renderer = $this->getRenderer();
        $table = $this->createTable(PostEmbedType::class);

        $html = $renderer->renderTable($table->createView());

        $expected = <<<EOS
page=1
limit=10
pages=1
total=3
field=id
property_path=id
field=date
property_path=embed.date
field=author
property_path=author.name
field=author_email
property_path=author.email
field=isActive
property_path=isActive
field=content
property_path=content

EOS;

        $this->assertEquals($expected, $html);
    }

    /**
     * Disabled fields are not passed through view
     */
    public function testRenderTableWithDisabledFields()
    {
        $renderer = $this->getRenderer();
        $table = $this->createTable(PostCommentsType::class, array(
            'limit' => 12,
        ));

        $html = $renderer->renderTable($table->createView());

        $expected = <<<EOS
page=1
limit=12
pages=1
total=3
field=id
property_path=id

EOS;

        $this->assertEquals($expected, $html);
    }

    /**
     * Render a javascript block of a table.
     */
    public function testRenderTableJavascript()
    {
        $renderer = $this->getRenderer();

        $html = $renderer->renderTable($this->view, 'javascript');

        $this->assertEquals('<script></script>', $html);
    }

    /**
     * Render a main part of a field
     */
    public function testRenderTextFieldMainBlock()
    {
        $renderer = $this->getRenderer();

        $html = $renderer->renderField($this->view->fields['content']);

        $expected = <<<EOS
block=field_text
name=content
label=content
property_path=content
EOS;

        $this->assertEquals($expected, $html);
    }

    /**
     * Render a secondary part of a field
     */
    public function testRenderTextFieldPart()
    {
        $renderer = $this->getRenderer();

        $html = $renderer->renderField($this->view->fields['content'], 'part');

        $this->assertEquals('extended', $html);
    }

    /**
     * Render a main part of a field, using default blocks
     */
    public function testRenderDefaultField()
    {
        $renderer = $this->getRenderer();

        $html = $renderer->renderField($this->view->fields['isActive']);

        $expected = <<<EOS
block=field
name=isActive
label=isActive
property_path=isActive
EOS;

        $this->assertEquals($expected, $html);
    }

    /**
     * Render a secondary part of a field, using default blocks
     */
    public function testRenderDefaultFieldPart()
    {
        $renderer = $this->getRenderer();

        $html = $renderer->renderField($this->view->fields['isActive'], 'part');

        $this->assertEquals('field_part', $html);
    }

    /**
     * Render a secondary part of a field, using default blocks
     */
    public function testRenderTableThrowsIfBlockNotFound()
    {
        $this->setExpectedException('RuntimeException');

        $this->getRenderer()->renderTable($this->view, 'foo');
    }

    /**
     * Render a secondary part of a field, using default blocks
     */
    public function testRenderFieldThrowsIfBlockNotFound()
    {
        $this->setExpectedException('RuntimeException');

        $this->getRenderer()->renderField($this->view->fields['id'], 'bar');
    }

    /**
     * @return TwigRenderer
     */
    private function getRenderer()
    {
        // Need to load Twig environment first
//        $this->get('twig')->initRuntime();

        return $this->get('nours_table.table_renderer.twig');
    }
}