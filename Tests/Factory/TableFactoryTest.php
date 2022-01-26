<?php

namespace Nours\TableBundle\Tests\Factory;

use Nours\TableBundle\Factory\TableFactory;
use Nours\TableBundle\Field\FieldInterface;
use Nours\TableBundle\Field\FieldTypeInterface;
use Nours\TableBundle\Field\Type\BooleanType;
use Nours\TableBundle\Field\Type\CheckboxType;
use Nours\TableBundle\Field\Type\CollectionType;
use Nours\TableBundle\Field\Type\DateType;
use Nours\TableBundle\Field\Type\HiddenType;
use Nours\TableBundle\Field\Type\LabelType;
use Nours\TableBundle\Field\Type\PrototypeType;
use Nours\TableBundle\Field\Type\TextType;
use Nours\TableBundle\Table\TableInterface;
use Nours\TableBundle\Tests\FixtureBundle\Field\FQCNFieldType;
use Nours\TableBundle\Tests\FixtureBundle\Table\PagerType;
use Nours\TableBundle\Tests\FixtureBundle\Table\PostCommentsType;
use Nours\TableBundle\Tests\FixtureBundle\Table\PostType;
use Nours\TableBundle\Tests\TestCase;

class TableFactoryTest extends TestCase
{
    /**
     * @var TableFactory
     */
    private $factory;

    protected function setUp(): void
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
     * Tests the bundled field types
     */
    public function testGetFieldType()
    {
        $this->assertInstanceOf(FieldTypeInterface::class, $this->factory->getFieldType(BooleanType::class));
        $this->assertInstanceOf(FieldTypeInterface::class, $this->factory->getFieldType(CheckboxType::class));
        $this->assertInstanceOf(FieldTypeInterface::class, $this->factory->getFieldType(CollectionType::class));
        $this->assertInstanceOf(FieldTypeInterface::class, $this->factory->getFieldType(DateType::class));
        $this->assertInstanceOf(FieldTypeInterface::class, $this->factory->getFieldType(HiddenType::class));
        $this->assertInstanceOf(FieldTypeInterface::class, $this->factory->getFieldType(LabelType::class));
        $this->assertInstanceOf(FieldTypeInterface::class, $this->factory->getFieldType(PrototypeType::class));
        $this->assertInstanceOf(FieldTypeInterface::class, $this->factory->getFieldType(TextType::class));
    }

    /**
     * Text type
     */
    public function testCreateTextTypeField()
    {
        /** @var FieldInterface $field */
        $field = $this->factory->createField('test', TextType::class, array(
            'label' => 'The Text Field',
            'sortable' => true,
            'searchable' => true
        ));
        $this->assertNotNull($field);
        $this->assertEquals('test', $field->getName());
        $this->assertEquals(TextType::class, get_class($field->getType()));
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
        $field = $this->factory->createField('testBool', BooleanType::class, array(
            'label' => 'testBool label'
        ));
        $this->assertNotNull($field);
        $this->assertEquals('testBool', $field->getName());
        $this->assertEquals(BooleanType::class, get_class($field->getType()));
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
        $field = $this->factory->createField('testDate', DateType::class);
        $this->assertNotNull($field);
        $this->assertEquals('testDate', $field->getName());
        $this->assertEquals(DateType::class, get_class($field->getType()));

        $this->assertFalse($field->getOption('sortable'));
        $this->assertFalse($field->getOption('searchable'));
    }

    /**
     * Text type
     */
    public function testCreateLabelTypeField()
    {
        /** @var FieldInterface $field */
        $field = $this->factory->createField('state', LabelType::class);
        $this->assertNotNull($field);
        $this->assertEquals('state', $field->getName());
        $this->assertEquals(LabelType::class, get_class($field->getType()));

        $this->assertFalse($field->getOption('sortable'));
        $this->assertFalse($field->getOption('searchable'));
    }

    /**
     * Text type
     */
    public function testCreateFieldUsingFQCN()
    {
        /** @var FieldInterface $field */
        $field = $this->factory->createField('test', TextType::class, array(
            'label' => 'The Text Field',
            'sortable' => true,
            'searchable' => true
        ));
        $this->assertNotNull($field);
        $this->assertEquals('test', $field->getName());
        $this->assertEquals(TextType::class, get_class($field->getType()));
    }

    /**
     * Text type
     */
    public function testCreateFQCNField()
    {
        /** @var FieldInterface $field */
        $field = $this->factory->createField('test', FQCNFieldType::class);
        $this->assertNotNull($field);
    }

    /**
     * Text type
     */
    public function testCreateTable()
    {
        /** @var TableInterface $table */
        $table = $this->factory->createTable(PostType::class);

        $this->assertNotNull($table);
        $this->assertNull($table->getData());
        $this->assertEquals('post', $table->getName());

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

    /**
     * Create table using class notation
     */
    public function testCreateTableUsingFQCN()
    {
        $table = $this->factory->createTable(PostType::class);

        $this->assertNotNull($table);
        $this->assertEquals('post', $table->getName());
    }

    /**
     * Calling createTable multiple times returns correct instance.
     */
    public function testCreateTableMultipleTimes()
    {
        $tablePost    = $this->factory->createTable(PostType::class);
        $tableComment = $this->factory->createTable(PostCommentsType::class);

        $this->assertEquals('post', $tablePost->getName());
        $this->assertEquals('post_comments', $tableComment->getName());
    }
} 