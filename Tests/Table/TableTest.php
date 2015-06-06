<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Tests\Table;

use Nours\TableBundle\Tests\TestCase;
use Pagerfanta\Adapter\FixedAdapter;
use Pagerfanta\Pagerfanta;

/**
 * Class TableTest
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TableTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testGetOptions()
    {
        $table = $this->createTable('post', array(
            'page' => 2,
            'limit' => 15
        ));

        $options = $table->getOptions();
        $this->assertEquals(2, $options['page']);
        $this->assertEquals(15, $options['limit']);
    }

    public function testCreateView()
    {
        $table = $this->getTableFactory()->createTable('post', array(
            'page' => 2,
            'limit' => 15
        ));

        $view = $table->createView();

        $this->assertInstanceOf('Nours\TableBundle\Table\View', $view);

        $vars = $view->vars;
        $this->assertEquals(2, $vars['page']);
        $this->assertEquals(15, $vars['limit']);

        $this->assertCount(4, $view->fields);

        $fieldView = $view->fields[0];
        $this->assertInstanceOf('Nours\TableBundle\Table\View', $fieldView);
        $vars = $fieldView->vars;
        $this->assertFalse($vars['sortable']);
        $this->assertFalse($vars['searchable']);

        $fieldView = $view->fields[1];
        $this->assertInstanceOf('Nours\TableBundle\Table\View', $fieldView);
        $vars = $fieldView->vars;
        $this->assertTrue($vars['sortable']);
        $this->assertTrue($vars['searchable']);

        $fieldView = $view->fields[2];
        $this->assertInstanceOf('Nours\TableBundle\Table\View', $fieldView);
        $vars = $fieldView->vars;
        $this->assertEquals('yes', $vars['true_text']);
        $this->assertEquals('no', $vars['false_text']);
        $this->assertFalse($vars['sortable']);
        $this->assertFalse($vars['searchable']);

        $fieldView = $view->fields[3];
        $this->assertInstanceOf('Nours\TableBundle\Table\View', $fieldView);
        $vars = $fieldView->vars;
        $this->assertFalse($vars['sortable']);
        $this->assertFalse($vars['searchable']);
    }

    public function testViewSerialization()
    {
        $serializer = $this->get('jms_serializer');

        $data = array(
            array('id' => 1),
            array('id' => 2)
        );
        $adapter = new FixedAdapter(2, $data);
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage(20)->setCurrentPage(1);

        $view = $this->getTableFactory()->createTable('post', array(
            'pager' => $pager
        ))->createView();

        $serialized = $serializer->serialize($view, 'json');


        $object = json_decode($serialized, true);

        $this->assertEquals(1, $object['page']);
        $this->assertEquals(20, $object['limit']);
        $this->assertEquals(1, $object['pages']);
        $this->assertEquals(2, $object['total']);
        $this->assertEquals($data, $object['data']);
        $this->assertEquals(true, $object['sortable']);
        $this->assertEquals(true, $object['searchable']);
        $this->assertCount(4, $object['fields']);
    }
}