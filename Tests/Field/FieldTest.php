<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Tests\Field;

use Nours\TableBundle\Field\FieldInterface;
use Nours\TableBundle\Field\Type\TextType;
use Nours\TableBundle\Tests\FixtureBundle\Field\ExtendedTextType;
use Nours\TableBundle\Tests\FixtureBundle\Table\PostType;
use Nours\TableBundle\Tests\TestCase;

/**
 * Class FieldTest
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class FieldTest extends TestCase
{
    public function setUp()
    {

    }

    public function testFieldTypeWithParent()
    {
        /** @var FieldInterface $field */
        $field = $this->get('nours_table.factory')->createField('test', ExtendedTextType::class, array(
            'strip_tags' => true
        ));

        $this->assertNotNull($field->getParent());
        $this->assertEquals(TextType::class, get_class($field->getParent()));
    }

    public function testFieldTypeWithParentView()
    {
        $view = $this->get('nours_table.factory')->createTable(PostType::class, array(
        ))->createView();

        $fieldView = $view->fields['content'];

        $this->assertCount(3, $fieldView->vars['block_prefixes']);
    }
}