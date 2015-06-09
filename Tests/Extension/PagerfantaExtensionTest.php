<?php

namespace Nours\TableBundle\Tests\Factory;


use Nours\TableBundle\Table\Factory\TableFactory;
use Nours\TableBundle\Tests\TestCase;
use Pagerfanta\Adapter\FixedAdapter;
use Pagerfanta\Pagerfanta;

class PagerfantaExtensionTest extends TestCase
{
    /**
     * @var TableFactory
     */
    private $factory;

    public function setUp()
    {
        parent::setUp();

        $this->factory = $this->getTableFactory();
    }

    /**
     * The params are loaded from pager
     */
    public function testPagerOption()
    {
        $data = array(
            array('id' => 1),
            array('id' => 2)
        );
        $adapter = new FixedAdapter(2, $data);
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage(20)->setCurrentPage(1);

        $table = $this->factory->createTable('pager', array(
            'pager' => $pager
        ))->handle();

        $this->assertEquals(1, $table->getPage());
        $this->assertEquals(20, $table->getLimit());
        $this->assertEquals(1, $table->getPages());
        $this->assertEquals(2, $table->getTotal());
        $this->assertEquals($data, $table->getData());
    }
} 