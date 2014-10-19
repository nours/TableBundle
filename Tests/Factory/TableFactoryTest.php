<?php

namespace Nours\TableBundle\Tests\Factory;


use Nours\TableBundle\Tests\TableTestCase;

class TableFactoryTest extends TableTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testAddGetExtensions()
    {
        $extension = $this->prophesize('Nours\TableBundle\Table\ExtensionInterface')->reveal();

        $this->factory->addTableExtension($extension);

        $extensions = $this->factory->getExtensions();

        $this->assertContains($extension, $extensions);
    }
} 