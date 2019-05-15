<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Tests\FixtureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Searchable
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 *
 * @ORM\Entity()
 */
class Searchable
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $searchBegin;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $searchInside;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $searchEnd;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $searchWord;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $searchCustom;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSearchBegin()
    {
        return $this->searchBegin;
    }

    /**
     * @param string $searchBegin
     */
    public function setSearchBegin($searchBegin)
    {
        $this->searchBegin = $searchBegin;
    }

    /**
     * @return string
     */
    public function getSearchInside()
    {
        return $this->searchInside;
    }

    /**
     * @param string $searchInside
     */
    public function setSearchInside($searchInside)
    {
        $this->searchInside = $searchInside;
    }

    /**
     * @return string
     */
    public function getSearchEnd()
    {
        return $this->searchEnd;
    }

    /**
     * @param string $searchEnd
     */
    public function setSearchEnd($searchEnd)
    {
        $this->searchEnd = $searchEnd;
    }

    /**
     * @return string
     */
    public function getSearchWord()
    {
        return $this->searchWord;
    }

    /**
     * @param string $searchWord
     */
    public function setSearchWord($searchWord)
    {
        $this->searchWord = $searchWord;
    }

    /**
     * @return string
     */
    public function getSearchCustom()
    {
        return $this->searchCustom;
    }

    /**
     * @param string $searchCustom
     */
    public function setSearchCustom($searchCustom)
    {
        $this->searchCustom = $searchCustom;
    }

    public function toArray()
    {
        return array(
            'searchBegin' => $this->searchBegin,
            'searchInside' => $this->searchInside,
            'searchEnd' => $this->searchEnd,
            'searchWord' => $this->searchWord,
            'searchCustom' => $this->searchCustom,
        );
    }
}