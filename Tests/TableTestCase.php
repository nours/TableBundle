<?php

namespace Nours\TableBundle\Tests;


use Nours\TableBundle\Builder\TableBuilder;
use Nours\TableBundle\Factory\TableFactory;
use Nours\TableBundle\Field\Type\TextType;
use Nours\TableBundle\Table\TableInterface;
use Nours\TableBundle\Tests\Fixtures\TableTestType;
use Prophecy\PhpUnit\ProphecyTestCase;

class TableTestCase extends ProphecyTestCase
{
    /**
     * @var TableFactory
     */
    protected $factory;

    /**
     * @var TableBuilder
     */
    protected $builder;

    /**
     * @var TableInterface
     */
    protected $table;

    public function setUp()
    {
        parent::setUp();

        $this->factory = new TableFactory();
        $this->factory->addFieldType(new TextType());

        $this->factory->addTableType(new TableTestType());

        $this->table = $this->factory->createTable('test');
    }
} 