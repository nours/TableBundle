<?php

namespace Nours\TableBundle\Tests\Factory;

use Nours\TableBundle\Factory\TableFactory;
use Nours\TableBundle\Field\FieldInterface;
use Nours\TableBundle\Table\TableInterface;
use Nours\TableBundle\Tests\TestCase;

class TableFactoryTest extends TestCase
{
    /**
     * @var TableFactory
     */
    private $factory;

    public function setUp()
    {
        parent::setUp();

        $this->factory = $this->get('nours_table.table_factory');
    }

    /**
     * The bundle comes with two extensions which are enabled in the test env.
     *
     * Their order is important and defined in DI Extension.
     */
    public function testHasExtensions()
    {
        $extensions = $this->factory->getExtensions();

        $this->assertCount(2, $extensions);
        $this->assertInstanceOf('Nours\TableBundle\Extension\PagerfantaExtension', $extensions[0]);
        $this->assertInstanceOf('Nours\TableBundle\Extension\DoctrineORMExtension', $extensions[1]);
    }

    /**
     * Text type
     */
    public function testCreateTextTypeField()
    {
        /** @var FieldInterface $field */
        $field = $this->factory->createField('test', 'text', array(
            'label' => 'The Text Field',
            'sortable' => true,
            'searchable' => true
        ));
        $this->assertNotNull($field);
        $this->assertEquals('test', $field->getName());
        $this->assertEquals('text', $field->getType());
        $this->assertEquals('The Text Field', $field->getLabel());

        $this->assertTrue($field->isSortable());
        $this->assertTrue($field->isSearchable());
    }

    /**
     * Text type
     */
    public function testCreateBooleanTypeField()
    {
        /** @var FieldInterface $field */
        $field = $this->factory->createField('testBool', 'boolean');
        $this->assertNotNull($field);
        $this->assertEquals('testBool', $field->getName());
        $this->assertEquals('boolean', $field->getType());
        $this->assertEquals('testBool', $field->getLabel());

        $this->assertFalse($field->isSortable());
        $this->assertFalse($field->isSearchable());
    }

    /**
     * Text type
     */
    public function testCreateDateTypeField()
    {
        /** @var FieldInterface $field */
        $field = $this->factory->createField('testDate', 'date');
        $this->assertNotNull($field);
        $this->assertEquals('testDate', $field->getName());
        $this->assertEquals('date', $field->getType());

        $this->assertFalse($field->isSortable());
        $this->assertFalse($field->isSearchable());
    }

    /**
     * Text type
     */
    public function testCreateLabelTypeField()
    {
        /** @var FieldInterface $field */
        $field = $this->factory->createField('state', 'label');
        $this->assertNotNull($field);
        $this->assertEquals('state', $field->getName());
        $this->assertEquals('label', $field->getType());

        $this->assertFalse($field->isSortable());
        $this->assertFalse($field->isSearchable());
    }

    /**
     * Text type
     */
    public function testCreateTable()
    {
        /** @var TableInterface $table */
        $table = $this->factory->createTable('post');
        $this->assertNotNull($table);
        $this->assertNull($table->getData());
    }
} 