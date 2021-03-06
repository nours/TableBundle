<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Field;

use Nours\TableBundle\Table\View;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface FieldTypeInterface
{
    /**
     * Creates a field for this type.
     *
     * @param string $name
     * @param array $options
     * @param array $ancestors
     * @return FieldInterface
     */
    public function createField($name, array $options, array $ancestors);
    
    /**
     * The name of this field type.
     * 
     * @return string
     */
    public function getName();

    /**
     * The name of the block prefix for rendering.
     *
     * @return string
     */
    public function getBlockPrefix();

    /**
     * This type can inherit another's properties returning it's name by this function.
     *
     * @return string|null
     */
    public function getParent();
    
    /**
     * Configures default options for this field.
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Builds a view for this field type.
     *
     * @param View $view
     * @param FieldInterface $field
     * @param array $options
     */
    public function buildView(View $view, FieldInterface $field, array $options);
}