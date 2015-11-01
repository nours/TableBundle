<?php

namespace Nours\TableBundle\Tests\Factory;

use Nours\TableBundle\Factory\TableFactory;
use Nours\TableBundle\Field\FieldInterface;
use Nours\TableBundle\Table\TableInterface;
use Nours\TableBundle\Tests\FixtureBundle\Table\PagerType;
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

        $this->factory = $this->getTableFactory();
    }

    /**
     * The bundle comes with 3 extensions (2 of which are enabled in the test env).
     */
    public function testGetExtensions()
    {
        $extensions = $this->factory->getExtensions();

        $this->assertCount(4, $extensions);
        $this->assertInstanceOf('Nours\TableBundle\Extension\CoreExtension', $extensions['core']);
        $this->assertInstanceOf('Nours\TableBundle\Extension\FormExtension', $extensions['form']);
        $this->assertInstanceOf('Nours\TableBundle\Extension\DoctrineORMExtension', $extensions['orm']);
        $this->assertInstanceOf('Nours\TableBundle\Extension\BootstrapTableExtension', $extensions['bootstrap_table']);
    }

    /**
     * The bundle comes with 3 extensions (2 of which are enabled in the test env).
     */
    public function testGetExtensionsForType()
    {
        $extensions = $this->factory->getExtensionsForType(new PagerType());

        $this->assertCount(1, $extensions);
        $this->assertInstanceOf('Nours\TableBundle\Extension\CoreExtension', $extensions[0]);
    }

    /**
     * The bundle comes with types
     */
    public function testGetType()
    {
        $text = $this->factory->getFieldType('text');

        $this->assertEquals('text', $text->getName());
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
        $this->assertEquals('text', $field->getTypeName());
        $this->assertEquals('The Text Field', $field->getLabel());

        $this->assertTrue($field->getOption('sortable'));
        $this->assertTrue($field->getOption('searchable'));
    }

    /**
     * Text type
     */
    public function testCreateBooleanTypeField()
    {
        /** @var FieldInterface $field */
        $field = $this->factory->createField('testBool', 'boolean', array(
            'label' => 'testBool label'
        ));
        $this->assertNotNull($field);
        $this->assertEquals('testBool', $field->getName());
        $this->assertEquals('boolean', $field->getTypeName());
        $this->assertEquals('testBool label', $field->getLabel());

        $this->assertFalse($field->getOption('sortable'));
        $this->assertFalse($field->getOption('searchable'));
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
        $this->assertEquals('date', $field->getTypeName());

        $this->assertFalse($field->getOption('sortable'));
        $this->assertFalse($field->getOption('searchable'));
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
        $this->assertEquals('label', $field->getTypeName());

        $this->assertFalse($field->getOption('sortable'));
        $this->assertFalse($field->getOption('searchable'));
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
        $this->assertEquals('post', $table->getName());
        $this->assertEquals('post', $table->getOption('name'));

        // The type have 5 fields
        $this->assertCount(5, $table->getFields());

        // All fields must have references to their table
        foreach ($table->getFields() as $field) {
            $this->assertSame($table, $field->getTable());
        }

        // The table is searchable and sortable
        $this->assertTrue($table->getOption('sortable'));
//        $this->assertTrue($table->getOption('searchable'));
//        $this->assertTrue($table->getOption('filterable'));
    }
} 