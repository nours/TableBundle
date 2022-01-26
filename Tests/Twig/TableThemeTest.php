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

use Nours\TableBundle\Tests\FixtureBundle\Table\PostType;
use Nours\TableBundle\Tests\TestCase;
use Twig\Environment;

/**
 * Class TableThemeTest
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TableThemeTest extends TestCase
{
    /**
     * @var Environment
     */
    private $twig;

    protected function setUp(): void
    {
        $this->twig = $this->get('twig');
    }

    /**
     * Checks table theme tag
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function testTableTheme()
    {
        $html = $this->twig->render('table_theme.html.twig', array(
            'table' => $this->createTable(PostType::class, array(
                'limit' => 10,
            ))->createView()
        ));

        $this->assertRegexp('/table_html/', $html);
        $this->assertRegexp('/table_javascript/', $html);
    }
}