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
use Nours\TableBundle\Tests\TestCase;

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

    public function setUp()
    {
        parent::setUp();

        $this->factory = $this->getTableFactory();
    }

    /**
     * Core params are loaded from options.
     */
    public function testCoreParameters()
    {
        $data = array(
            array('id' => 1),
            array('id' => 2),
            array('id' => 3),
            array('id' => 4)
        );

        $table = $this->factory->createTable('post', array(
            'page' => 1,
            'limit' => 10,
            'pages' => 2,
            'total' => 50,
            'data' => $data,
        ));

        $this->assertEquals(1, $table->getPage());
        $this->assertEquals(10, $table->getLimit());
        $this->assertEquals(2, $table->getPages());
        $this->assertEquals(50, $table->getTotal());
        $this->assertEquals($data, $table->getData());
    }
}