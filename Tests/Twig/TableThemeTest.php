<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Tests\Twig;

use Nours\TableBundle\Tests\TestCase;

/**
 * Class TableThemeTest
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TableThemeTest extends TestCase
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function setUp()
    {
        $this->twig = $this->get('twig');
    }

    /**
     * Checks table theme tag
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testTableTheme()
    {
        $html = $this->twig->render('table_theme.html.twig', array(
            'table' => $this->createTable('post', array(
                'limit' => 10,
            ))->createView()
        ));

        $this->assertRegexp('/table_html/', $html);
        $this->assertRegexp('/table_javascript/', $html);
    }
}