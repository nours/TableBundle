<?php

namespace Nours\TableBundle\Table;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Nours\TableBundle\Builder\TableBuilder;
use Nours\TableBundle\Builder\TableBuilderInterface;
use Nours\TableBundle\Factory\TableFactoryInterface;

/**
 * Abstract type for tables.
 * 
 * Inherit from this base class in order to build new tables.
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
abstract class AbstractType implements TableTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function createBuilder($name, TableFactoryInterface $factory, array $options = array())
    {
	    // Configure options resolver
        $resolver = $this->getOptionsResolver();
	    
	    $this->setDefaultOptions($resolver);
        foreach ($factory->getExtensions() as $extension) {
            $extension->setDefaultOptions($resolver);
        }

		$builder = new TableBuilder($name, $factory, $resolver, $options);
		
		$this->buildTable($builder, $options);

        // Extensions
        foreach ($factory->getExtensions() as $extension) {
            $extension->buildTable($builder, $options);
        }
		
		return $builder;
    }
    
    
    private function getOptionsResolver()
    {
        $resolver = new OptionsResolver();
        
        $resolver->setDefaults(array(
            'fields'  => null,
            'page'  => 1,
            'limit' => 10,
            'pages' => null,
            'total' => null,
            'data'  => null,
            'url'   => null,
            'row_style' => false
        ));
        
        return $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        
    }

    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilderInterface $builder, array $options)
    {
        
    }
}