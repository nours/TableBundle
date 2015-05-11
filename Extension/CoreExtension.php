<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Extension;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Nours\TableBundle\Table\TableInterface;


/**
 * Class CoreExtension
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class CoreExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'page'    => 1,
            'limit'   => 10,
            'pages'   => null,
            'total'   => null,
            'data'    => null,
            'url'     => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function loadTable(TableInterface $table, array $options)
    {
        if ($data = $options['data']) {
            $table->setData($data);
        }
        if ($pages = $options['pages']) {
            $table->setPages($pages);
        }
        if ($total = $options['total']) {
            $table->setTotal($total);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'core';
    }
}