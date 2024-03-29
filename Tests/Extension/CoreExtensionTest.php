<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Tests\Extension;

use Nours\TableBundle\Factory\TableFactory;
use Nours\TableBundle\Tests\FixtureBundle\Table\PostType;
use Nours\TableBundle\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CoreExtensionTest
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class CoreExtensionTest extends TestCase
{
    /**
     * @var TableFactory
     */
    private $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = $this->getTableFactory();
    }

    /**
     * Core params are loaded from options.
     */
    public function testCreateTable()
    {
        $data = array(
            array('id' => 1),
            array('id' => 2),
            array('id' => 3),
            array('id' => 4)
        );

        $table = $this->factory->createTable(PostType::class, array(
            'page' => 1,
            'limit' => 10,
            'pages' => 2,
            'total' => 50,
            'data' => $data,
        ));

        $this->assertEquals(1,     $table->getPage());
        $this->assertEquals(10,    $table->getLimit());
        $this->assertEquals(2,     $table->getPages());
        $this->assertEquals(50,    $table->getTotal());
        $this->assertEquals($data, $table->getData());
        $this->assertEquals(true,  $table->getOption('pagination'));
        $this->assertEquals(null,  $table->getOption('sort'));
        $this->assertEquals('ASC', $table->getOption('order'));
    }

    /**
     * Table's view.
     */
    public function testTableView()
    {
        $data = array(
            array('id' => 1),
            array('id' => 2),
            array('id' => 3),
            array('id' => 4)
        );

        $view = $this->factory->createTable(PostType::class, array(
            'page' => 1,
            'limit' => 10,
            'pages' => 2,
            'total' => 50,
            'pagination' => false,
            'data' => $data,
            'sort' => 'id'
        ))->createView();

        $vars = $view->vars;
        $this->assertEquals(1,  $vars['page']);
        $this->assertEquals(10, $vars['limit']);
        $this->assertEquals(2,  $vars['pages']);
        $this->assertEquals(50, $vars['total']);
        $this->assertEquals($data, $view->getData());
        $this->assertEquals(false, $vars['pagination']);
        $this->assertEquals('id',  $vars['sort']);
        $this->assertEquals('ASC', $vars['order']);
    }

    /**
     * Table's view.
     */
    public function testTableHandle()
    {
        $this->loadFixtures();

        $table = $this->factory->createTable(PostType::class, array(
        ));

        $table->handle(new Request(array(
            'sort' => 'status',
            'page' => 1,
            'limit' => 33,
        )));

        $this->assertEquals(1,  $table->getPage());
        $this->assertEquals(33, $table->getLimit());
        $this->assertEquals(1,  $table->getPages());
        $this->assertEquals(3,  $table->getTotal());
        $this->assertEquals('status',  $table->getOption('sort'));
    }
}