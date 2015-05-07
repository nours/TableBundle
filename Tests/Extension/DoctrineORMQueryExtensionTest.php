<?php

namespace Nours\TableBundle\Tests\Factory;


use Nours\TableBundle\Extension\DoctrineORMExtension;
use Nours\TableBundle\Extension\PagerfantaExtension;
use Nours\TableBundle\Tests\TableTestCase;

class DoctrineORMQueryExtensionTest extends TableTestCase
{
    /**
     * @var PagerfantaExtension
     */
    private $extension;

    public function setUp()
    {
        parent::setUp();

        $this->extension = new DoctrineORMExtension();
        $this->factory->addTableExtension($this->extension);
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
        $this->factory->createTable('test');
    }
} 