<?php

namespace Nours\TableBundle\Tests\Factory;

use Nours\TableBundle\Factory\TypesRegistry;
use Nours\TableBundle\Field\Type\TextType;
use Nours\TableBundle\Tests\FixtureBundle\Table\PostType;
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

        $this->registry = new TypesRegistry();
    }

    /**
     * Test FixtureBundle table types
     */
    public function testGetTableType()
    {
        $type = new PostType();
        $this->registry->setTableType($type);

        $this->assertSame($type, $this->registry->getTableType('post'));
    }

    /**
     * Test FixtureBundle field types
     */
    public function testGetDefaultFieldType()
    {
        $type = new TextType();
        $this->registry->setFieldType($type);

        $this->assertSame($type,  $this->registry->getFieldType('text'));
    }
}