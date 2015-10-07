<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Tests\Extension;

use Nours\TableBundle\Tests\FixtureBundle\Entity\Post;
use Nours\TableBundle\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FormFilterExtensionTest
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class FormFilterExtensionTest extends TestCase
{
    public function testFormFilter()
    {
        $this->loadFixtures();

        $table = $this->createTable('post');

        $table->handle(new Request(array(
            'filter' => array(
                'status' => Post::STATUS_EDITING
            )
        )));

        $params = $table->getOption('filter_data');

        $this->assertNotNull($params);
        $this->assertCount(1, $params);
        $this->assertEquals(Post::STATUS_EDITING, $params['status']);
    }


    public function testFormViewIsCreated()
    {
        $table = $this->createTable('post');

        $view = $table->createView();

        $form = $view->vars['form'];
        $this->assertInstanceOf('Symfony\Component\Form\FormView', $form);
    }
}