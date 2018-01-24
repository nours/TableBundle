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

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Class TestKernel
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TestKernel extends Kernel
{
    public function registerBundles()
    {
        return array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Nours\TableBundle\NoursTableBundle(),
            new \Nours\TableBundle\Tests\FixtureBundle\FixtureBundle(),
            new \JMS\SerializerBundle\JMSSerializerBundle(),
            new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle()
        );
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_' .$this->getEnvironment() . '.yml');
    }
}