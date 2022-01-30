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
use Nours\TableBundle\Table\View;
use Nours\TableBundle\Field\FieldInterface;
use Nours\TableBundle\Table\TableInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class BootstrapTableExtension
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class BootstrapTableExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(View $view, TableInterface $table, array $options)
    {
        $idToolbar = $table->getName() . '_toolbar';

        $configs = array(
            'pagination' => $options['pagination'],
            'pageNumber' => $table->getPage(),
            'totalRows'  => $table->getTotal(),
            'pageSize'   => $table->getLimit(),
            'url'        => $options['url'],
            'sidePagination' => $options['url'] ? 'server' : 'client',
            'search'     => $table->getOption('searchable'),
            'toolbar'    => '#' . $idToolbar,
            'showRefresh' => $options['show_refresh']
        );

        if ($url = $options['url']) {
            $configs['url'] = $url;
        } else {
            // Data may be resolved here
            $configs['data'] = $table->getData();
        }
        if ($sort = $options['sort']) {
            $configs['sortName']  = $sort;
            $configs['sortOrder'] = strtolower($options['order'] ?: 'ASC');
        }
        if ($rowStyle = $options['row_style']) {
            $configs['row_style'] = $rowStyle;
        }

        if ($configs['cookie'] = $options['cookie']) {
            $configs['cookieIdTable'] = 'table_' . $table->getName();
        }

        $view->vars['configs']    = $configs;
        $view->vars['id_toolbar'] = $idToolbar;

        $attrs = $options['attr'];
        $view->vars['attr'] = $attrs;
    }

    /**
     * {@inheritdoc}
     */
    public function buildFieldView(View $view, FieldInterface $field, array $options)
    {
        $view->vars['formatter_name'] = $field->getTable()->getName() . '_' . $field->getName();
        $view->vars['configs']        = $options['configs'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'row_style'     => null,
            'cookie'        => false,
            'show_refresh'  => false, // Show refresh button
            'attr' => array()  // Table attributes
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureFieldOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'configs' => array(),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getDependency()
    {
        return 'form';
    }

    /**
     * Returns the name of this extension.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'bootstrap_table';
    }
}