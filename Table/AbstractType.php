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
	    // Options resolver
        $resolver = $this->getOptionsResolver();
	    
	    $this->setDefaultOptions($resolver);
	    $options = $resolver->resolve($options);
	    
		$builder = new TableBuilder($name, $factory, $options);
		
		$this->buildTable($builder, $options);
		
		return $builder;
    }
    
    
    private function getOptionsResolver()
    {
        $resolver = new OptionsResolver();
        
        $resolver->setDefaults(array(
            'page'  => null,
            'limit' => null,
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