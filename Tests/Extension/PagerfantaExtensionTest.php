<?php

namespace Nours\TableBundle\Tests\Factory;


use Nours\TableBundle\Builder\TableBuilder;
use Nours\TableBundle\Extension\PagerfantaExtension;
use Nours\TableBundle\Tests\TableTestCase;
use Pagerfanta\Adapter\FixedAdapter;
use Pagerfanta\Pagerfanta;

class PagerfantaExtensionTest extends TableTestCase
{
    /**
     * @var PagerfantaExtension
     */
    private $extension;

    public function setUp()
    {
        parent::setUp();

        $this->extension = new PagerfantaExtension();
        $this->factory->addTableExtension($this->extension);
    }

    public function testPagerOption()
    {
        $data = array(
            array('id' => 1),
            array('id' => 2)
        );
        $adapter = new FixedAdapter(2, $data);
        $pager = new Pagerfanta($adapter);

        $table = $this->factory->createTable('test', array(
            'pager' => $pager
        ));

        $this->assertEquals(1, $table->getPage());
        $this->assertEquals(10, $table->getLimit());
        $this->assertEquals(1, $table->getPages());
        $this->assertEquals(2, $table->getTotal());
        $this->assertEquals($data, $table->getData());
    }

    public function testCreateTableWithoutPager()
    {
        $this->factory->createTable('test');
    }
} 