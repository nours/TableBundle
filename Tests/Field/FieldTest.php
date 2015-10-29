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
        $field = $this->get('nours_table.factory')->createField('test', 'extended_text', array(
            'strip_tags' => true
        ));

        $this->assertNotNull($field->getParent());
        $this->assertEquals('text', $field->getParent()->getName());
    }

    public function testFieldTypeWithParentView()
    {
        $view = $this->get('nours_table.factory')->createTable('post', array(
        ))->createView();

        $fieldView = $view->fields['content'];

        $this->assertCount(3, $fieldView->vars['block_prefixes']);
    }
}