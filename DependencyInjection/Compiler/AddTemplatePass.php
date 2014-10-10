<?php
/**
 * Created by PhpStorm.
 * User: nours
 * Date: 30/08/14
 * Time: 15:00
 */

namespace Nours\TableBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AddTemplatePass implements CompilerPassInterface
{
    private $template;

    private $prepend;

    /**
     * @param $template
     * @param bool $prepend
     */
    public function __construct($template, $prepend = true)
    {
        $this->template = $template;
        $this->prepend = $prepend;
    }

    public function process(ContainerBuilder $container)
    {
        $templates = $container->getParameter('nours_table.default_templates');

        if ($this->prepend) {
            array_unshift($templates, $this->template);
        } else {
            array_push($templates, $this->template);
        }

        $container->setParameter('nours_table.default_templates', $templates);
    }
} 