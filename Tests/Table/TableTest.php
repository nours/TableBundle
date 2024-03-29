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

use Nours\TableBundle\Table\TableInterface;
use Nours\TableBundle\Tests\FixtureBundle\Table\FQCNTableType;
use Nours\TableBundle\Tests\FixtureBundle\Table\PagerType;
use Nours\TableBundle\Tests\FixtureBundle\Table\PostEmbedType;
use Nours\TableBundle\Tests\FixtureBundle\Table\PostType;
use Nours\TableBundle\Tests\TestCase;

/**
 * Class TableTest
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TableTest extends TestCase
{
    public function testGetOptions()
    {
        $table = $this->createTable(PostType::class, array(
            'page'  => '2',
            'limit' => '15'
        ));

        $options = $table->getOptions();
        $this->assertSame(2, $options['page']);
        $this->assertSame(15, $options['limit']);
    }

    public function testSetPage()
    {
        $table = $this->createTable(PostType::class);

        $table->setPage('123');
        $this->assertSame(123, $table->getPage());

        $this->expectException(\TypeError::class);
        $table->setPage('invalid');
    }

    public function testSetLimit()
    {
        $table = $this->createTable(PostType::class);

        $table->setLimit('123');
        $this->assertSame(123, $table->getLimit());

        $this->expectException(\TypeError::class);
        $table->setLimit('invalid');
    }

    public function testCreateView()
    {
        $table = $this->getTableFactory()->createTable(PostType::class, array(
            'limit' => 15
        ));

        $view = $table->createView();

        $this->assertInstanceOf('Nours\TableBundle\Table\View', $view);

        $vars = $view->vars;
        $this->assertEquals(1, $vars['page']);
        $this->assertEquals(15, $vars['limit']);
        $this->assertEquals('post', $vars['name']);

        $this->assertCount(5, $view->fields);

        $fieldView = $view->fields['id'];
        $this->assertInstanceOf('Nours\TableBundle\Table\View', $fieldView);
        $vars = $fieldView->vars;
        $this->assertEquals('id', $vars['name']);
        $this->assertEquals('id', $vars['property_path']);
        $this->assertTrue($vars['sortable']);
        $this->assertFalse($vars['searchable']);

        $fieldView = $view->fields['status'];
        $this->assertInstanceOf('Nours\TableBundle\Table\View', $fieldView);
        $vars = $fieldView->vars;
        $this->assertTrue($vars['sortable']);
        $this->assertTrue($vars['searchable']);

        $fieldView = $view->fields['isActive'];
        $this->assertInstanceOf('Nours\TableBundle\Table\View', $fieldView);
        $vars = $fieldView->vars;
        $this->assertEquals('isActive', $vars['name']);
        $this->assertEquals('isActive', $vars['label']);
        $this->assertEquals('isActive', $vars['property_path']);
        $this->assertEquals('yes', $vars['true_text']);
        $this->assertEquals('no', $vars['false_text']);
        $this->assertTrue($vars['sortable']);
        $this->assertFalse($vars['searchable']);

        $fieldView = $view->fields['content'];
        $this->assertInstanceOf('Nours\TableBundle\Table\View', $fieldView);
        $vars = $fieldView->vars;
        $this->assertFalse($vars['sortable']);
        $this->assertTrue($vars['searchable']);
    }

    public function testViewSerialization()
    {
        $serializer = $this->get('jms_serializer');

        $data = array(
            array('id' => 1),
            array('id' => 2)
        );

        $table = $this->getTableFactory()->createTable(PagerType::class, array(
            'data' => $data,
            'limit' => 20,
            'pages' => 1,
            'total' => 2
        ));
        $view = $table->createView();

        $serialized = $serializer->serialize($view, 'json');

        $object = json_decode($serialized, true);

        $this->assertEquals(1, $object['page']);
        $this->assertEquals(20, $object['limit']);
        $this->assertEquals(1, $object['pages']);
        $this->assertEquals(2, $object['total']);
        $this->assertEquals($data, $object['data']);
    }

    public function testViewExtraJsonSerialization()
    {
        $serializer = $this->get('jms_serializer');

        $table = $this->getTableFactory()->createTable(PagerType::class, array(
            'data' => array(),
            'json_vars' => array(
                'expected' => 'foo'
            )
        ));
        $view = $table->createView();

        $serialized = $serializer->serialize($view, 'json');

        $object = json_decode($serialized, true);

        $this->assertEquals('foo', $object['expected']);
    }

    public function testViewExtraCallbackJsonSerialization()
    {
        $serializer = $this->get('jms_serializer');
        $data = array(
            array('id' => 1),
            array('id' => 2)
        );

        $table = $this->getTableFactory()->createTable(PagerType::class, array(
            'data' => $data,
            'json_vars' => function(TableInterface $table) {
                return array(
                    'foo' => $table->getData(),
                    'foo_count' => 42
                );
            }
        ));
        $view = $table->createView();

        $serialized = $serializer->serialize($view, 'json');

        $object = json_decode($serialized, true);

        $this->assertEquals($data, $object['foo']);
        $this->assertEquals(42, $object['foo_count']);
    }

    public function testCreateAnotherView()
    {
        $table = $this->getTableFactory()->createTable(PostEmbedType::class);

        $view = $table->createView();

        $fieldView = $view->fields['author'];
        $vars = $fieldView->vars;
        $this->assertEquals('author.name', $vars['property_path']);
    }

    public function testBlockPrefix()
    {
        $table = $this->createTable(PostType::class);

        $prefix = $table->getType()->getBlockPrefix();
        $this->assertEquals('post', $prefix);
    }

    public function testBlockPrefixFromAnonymous()
    {
        $table = $this->createTable(FQCNTableType::class);

        $prefix = $table->getType()->getBlockPrefix();
        $this->assertEquals('f_q_c_n_table', $prefix);

        $view = $table->createView();
        $this->assertContains('table_f_q_c_n_table', $view->vars['block_prefixes']);
    }
}