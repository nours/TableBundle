<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Factory;

use Nours\TableBundle\Table\TableTypeInterface;
use Nours\TableBundle\Field\FieldTypeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class DependencyInjectionRegistry
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class DependencyInjectionRegistry implements TypesRegistryInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string[]
     */
    private $tableServices;

    /**
     * @var string[]
     */
    private $fieldServices;


    /**
     * @var TableTypeInterface[]
     */
    private $tableTypes;

    /**
     * @var FieldTypeInterface[]
     */
    private $fieldTypes;

    /**
     * @param ContainerInterface $container
     * @param array $tableServices
     * @param array $fieldServices
     */
    public function __construct(ContainerInterface $container, array $tableServices, array $fieldServices)
    {
        $this->container     = $container;
        $this->tableServices = $tableServices;
        $this->fieldServices = $fieldServices;
    }

    /**
     * @param string $name
     *
     * @return TableTypeInterface
     */
    public function getTableType($name)
    {
        if (!isset($this->tableTypes[$name])) {
            // Lazy load type
            if (!isset($this->tableServices[$name])) {
                return null;
            }

            $type = $this->container->get($this->tableServices[$name]);

//            if ($type->getName() != $name) {
//                throw new \InvalidArgumentException(sprintf(
//                    "Table type service alias (%s) for service '%s' do not match type name (%s)",
//                    $name, $this->tableServices[$name], $type->getName()
//                ));
//            }

            $this->tableTypes[$name] = $type;
        }

        return $this->tableTypes[$name];
    }

    /**
     * @param string $name
     *
     * @return FieldTypeInterface
     */
    public function getFieldType($name)
    {
        if (!isset($this->fieldTypes[$name])) {
            if (!isset($this->fieldServices[$name])) {
                return null;
            }

            $type = $this->container->get($this->fieldServices[$name]);

//            if ($type->getName() != $name) {
//                throw new \InvalidArgumentException(sprintf(
//                    "Field type service alias (%s) for service '%s' do not match type name (%s)",
//                    $name, $this->tableServices[$name], $type->getName()
//                ));
//            }

            $this->fieldTypes[$name] = $type;
        }

        return $this->fieldTypes[$name];
    }

    /**
     * @param TableTypeInterface $type
     */
    public function setTableType(TableTypeInterface $type)
    {
        $this->tableTypes[$type->getName()] = $type;
    }

    /**
     * @param FieldTypeInterface $type
     */
    public function setFieldType(FieldTypeInterface $type)
    {
        $this->fieldTypes[$type->getName()] = $type;
    }
}