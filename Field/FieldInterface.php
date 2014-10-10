<?php

namespace Nours\TableBundle\Field;

/**
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
interface FieldInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getPath();

    /**
     * @return string
     */
    public function getLabel();
    
    /**
     * @return array
     */
    public function getOptions();
}