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
    public function createField(string $name, array $options, array $ancestors): FieldInterface;
    
    /**
     * The name of the block prefix for rendering.
     *
     * @return string
     */
    public function getBlockPrefix(): string;

    /**
     * This type can inherit another's properties returning its name by this function.
     *
     * @return string|null
     */
    public function getParent(): ?string;
    
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