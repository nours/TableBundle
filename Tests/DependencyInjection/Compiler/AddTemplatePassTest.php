<?php

namespace Nours\TableBundle\Tests\DependencyInjection\Compiler;


use Nours\TableBundle\DependencyInjection\Compiler\AddTemplatePass;
use Prophecy\PhpUnit\ProphecyTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AddTemplatePassTest extends ProphecyTestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    public function setUp()
    {
        parent::setUp();

        $this->container = new ContainerBuilder();
        $this->container->setParameter('nours_table.default_templates', array('default.html.twig'));
    }


    public function testProcess()
    {
        $template = 'theme.html.twig';

        $pass = new AddTemplatePass($template, true);
        $pass->process($this->container);

        $templates = $this->container->getParameter('nours_table.default_templates');
        $this->assertEquals($template, $templates[0]);
    }
} 