<?php

namespace Nours\TableBundle\Tests\Factory;

use Nours\TableBundle\Factory\DependencyInjectionRegistry;
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
                'post'       => 'tests.table.post',
                'foo_bar'    => 'tests.table.post'
            ),
            array(
                'text'    => 'nours_table.table_field.text',
                'boolean' => 'nours_table.table_field.boolean',
                'foo_bar' => 'nours_table.table_field.boolean',
            )
        );
    }

    /**
     * Test FixtureBundle table types
     */
    public function testGetTableType()
    {
        $this->assertSame($this->getContainer()->get('tests.table.post'), $this->registry->getTableType('post'));
    }

    /**
     * Test FixtureBundle table types
     */
    public function testGetTableTypeThrowsIfTableNameDoNotExist()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->registry->getTableType('baz');
    }

    /**
     * Test FixtureBundle table types
     */
    public function testGetTableTypeThrowsIfTableNameDoNotMatchAlias()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->registry->getTableType('foo_bar');
    }

    /**
     * Test FixtureBundle field types
     */
    public function testGetFieldType()
    {
        $this->assertSame($this->getContainer()->get('nours_table.table_field.text'), $this->registry->getFieldType('text'));
        $this->assertSame($this->getContainer()->get('nours_table.table_field.boolean'), $this->registry->getFieldType('boolean'));
    }

    /**
     * Test FixtureBundle field types
     */
    public function testGetFieldTypeThrowsIfTableNameDoNotExist()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->registry->getFieldType('foo');
    }

    /**
     * Test FixtureBundle field types
     */
    public function testGetFieldTypeThrowsIfTableNameDoNotMatchAlias()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->registry->getFieldType('foo_bar');
    }
}