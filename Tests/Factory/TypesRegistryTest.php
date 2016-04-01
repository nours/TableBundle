<?php

namespace Nours\TableBundle\Tests\Factory;

use Nours\TableBundle\Factory\TypesRegistry;
use Nours\TableBundle\Tests\TestCase;

class TypesRegistryTest extends TestCase
{
    /**
     * @var TypesRegistry
     */
    private $registry;

    public function setUp()
    {
        parent::setUp();

        $this->registry = $this->get('nours_table.types_registry');
    }

    /**
     * Test FixtureBundle table types
     */
    public function testGetTableType()
    {
        $type = $this->registry->getTableType('post');
        $this->assertInstanceOf('Nours\TableBundle\Tests\FixtureBundle\Table\PostType', $type);

        $type = $this->registry->getTableType('pager');
        $this->assertInstanceOf('Nours\TableBundle\Tests\FixtureBundle\Table\PagerType', $type);
    }

    /**
     * Test FixtureBundle field types
     */
    public function testGetDefaultFieldType()
    {
        $type = $this->registry->getFieldType('text');
        $this->assertInstanceOf('Nours\TableBundle\Field\Type\TextType', $type);

        $type = $this->registry->getFieldType('collection');
        $this->assertInstanceOf('Nours\TableBundle\Field\Type\CollectionType', $type);
    }

    /**
     * Test FixtureBundle field types
     */
    public function testGetFixtureBundleFieldType()
    {
        $type = $this->registry->getFieldType('extended_text');
        $this->assertInstanceOf('Nours\TableBundle\Tests\FixtureBundle\Field\ExtendedTextType', $type);
    }
}