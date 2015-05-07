<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class TestCase
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TestCase extends KernelTestCase
{
    /**
     * @param string $service
     * @return mixed
     */
    protected function get($service)
    {
        return $this->getContainer()->get($service);
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected function getContainer()
    {
        if (empty(static::$kernel)) {
            static::bootKernel();
        }

        if (!static::$kernel->getContainer()) {
            static::$kernel->boot();
        }

        return static::$kernel->getContainer();
    }

    /**
     * Executes fixtures
     */
    protected function loadFixtures()
    {
        $loader = new Loader();
        $loader->loadFromDirectory(__DIR__ . '/FixtureBundle/Fixtures');

        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->getEntityManager(), $purger);
        $executor->execute($loader->getFixtures());
    }

    /**
     * Returns the doctrine orm entity manager
     *
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}