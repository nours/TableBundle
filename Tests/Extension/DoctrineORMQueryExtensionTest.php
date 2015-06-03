<?php

namespace Nours\TableBundle\Tests\Factory;


use Nours\TableBundle\Factory\TableFactory;
use Nours\TableBundle\Tests\TestCase;

class DoctrineORMQueryExtensionTest extends TestCase
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

    public function testCreateTable()
    {
//        $builder = $this->prophesize('Doctrine\ORM\QueryBuilder');
//
//        $this->factory->createTable('test', array(
//            'query_builder' => $builder->reveal(),
//            'page' => 1,
//            'limit' => 10,
//            'search' => 'test'
//        ));
    }

    public function testCreateTableWithoutQueryBuilder()
    {
        $this->factory->createTable('post');
    }
} 