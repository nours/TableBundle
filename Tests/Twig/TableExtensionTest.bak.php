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
use Nours\TableBundle\Twig\Extension\TableExtension;

/**
 * Class TableExtensionTest
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TableExtensionTest extends \Twig_Test_IntegrationTestCase
{
    protected function getExtensions()
    {
        return array(
            new TableExtension()
        );
    }

    /**
     * @return string
     */
    protected function getFixturesDir()
    {
        return dirname(dirname(__FILE__)) . '/FixtureBundle/Fixtures/twig/';
    }
}