<?php

namespace Nours\TableBundle\Tests\Factory;

use Nours\TableBundle\Factory\DependencyInjectionRegistry;
use Nours\TableBundle\Field\Type\BooleanType;
use Nours\TableBundle\Field\Type\TextType;
use Nours\TableBundle\Tests\FixtureBundle\Table\PostType;
use Nours\TableBundle\Tests\TestCase;

class DependencyInjectionRegistryTest extends TestCase
{
    /**
     * @var DependencyInjectionRegistry
     */
    private $registry;

    public function setUp()
    {
        parent::setUp();

        $this->registry = new DependencyInjectionRegistry(
            $this->get('service_container'),
            array(
                PostType::class       => 'tests.table.post'
            ),
            array(
                TextType::class    => 'nours_table.table_field.text',
                BooleanType::class => 'nours_table.table_field.boolean',
            )
        );
    }

    /**
     * Test FixtureBundle table types
     */
    public function testGetTableType()
    {
        $this->assertSame($this->getContainer()->get('tests.table.post'), $this->registry->getTableType(PostType::class));
    }

    /**
     * Test FixtureBundle field types
     */
    public function testGetFieldType()
    {
        $this->assertSame($this->getContainer()->get('nours_table.table_field.text'), $this->registry->getFieldType(TextType::class));
        $this->assertSame($this->getContainer()->get('nours_table.table_field.boolean'), $this->registry->getFieldType(BooleanType::class));
    }
}