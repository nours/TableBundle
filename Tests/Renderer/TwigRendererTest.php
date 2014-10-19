<?php

namespace Nours\TableBundle\Tests\Renderer;


use Nours\TableBundle\Tests\TableTestCase;
use Nours\TableBundle\Twig\Table\TwigRenderer;

class TwigRendererTest extends TableTestCase
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var TwigRenderer
     */
    private $renderer;

    /**
     * @var \Twig_Template
     */
    private $template;

    public function setUp()
    {
        parent::setUp();

        $this->template = $this->prophesize('Twig_Template');

        $this->twig = $this->prophesize('Twig_Environment');
        $this->twig->loadTemplate('NoursTableBundle:Table:theme.html.twig')
            ->willReturn($this->template->reveal());

        $this->renderer = new TwigRenderer(array(
            'NoursTableBundle:Table:theme.html.twig'
        ));
        $this->renderer->setEnvironment($this->twig->reveal());
    }

    public function testRenderTable()
    {
        $this->template->hasBlock('table_html')
            ->willReturn(true);
        $this->template->renderBlock('table_html', array(
            'table' => $this->table
        ))->willReturn('table');

        $html = $this->renderer->renderTable($this->table);

        $this->assertEquals('table', $html);
    }

    public function testRenderJavascript()
    {
        $this->template->hasBlock('table_javascript')
            ->willReturn(true);
        $this->template->renderBlock('table_javascript', array(
            'table' => $this->table,
            'row_style' => null
        ))->willReturn('javascript');

        $js = $this->renderer->renderJavascript($this->table);

        $this->assertEquals('javascript', $js);

    }

    public function testRenderField()
    {
//        $js = $this->renderer->renderField($this->table->getFields()[0]);

    }
} 