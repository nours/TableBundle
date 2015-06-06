<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Class AppKernel
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Nours\TableBundle\NoursTableBundle(),
            new Nours\TableBundle\Tests\FixtureBundle\FixtureBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle()
        );
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_test.yml');
    }
}