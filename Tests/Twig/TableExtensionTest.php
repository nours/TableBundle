<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Tests\Twig;

use Nours\TableBundle\Tests\TestCase;
use Nours\TableBundle\Twig\Extension\TableExtension;

/**
 * Class TableExtensionTest
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TableExtensionTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $renderer;

    /**
     * @var TableExtension
     */
    private $extension;

    public function setUp()
    {
        parent::setUp();

        $this->renderer = $this->getMockBuilder('Nours\TableBundle\Renderer\TwigRendererInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->extension = new TableExtension($this->renderer);
    }

    public function testRenderTable()
    {
        $table = $this->createTable('post');

        $this->renderer->expects($this->once())
            ->method('renderTable')
            ->willReturn('rendered');

        $html = $this->extension->renderTable($table->createView());

        $this->assertEquals('rendered', $html);
    }
}