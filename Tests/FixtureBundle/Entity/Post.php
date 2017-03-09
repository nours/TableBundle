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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Post
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 *
 * @ORM\Entity()
 */
class Post
{
    const STATUS_NEW       = 'new';
    const STATUS_EDITING   = 'editing';
    const STATUS_PUBLISHED = 'published';

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
     * @ORM\Column(type="string")
     */
    private $status = self::STATUS_NEW;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isActive = false;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Embedded(class="Embedded")
     */
    private $embed;

    /**
     * @var Author
     *
     * @ORM\ManyToOne(targetEntity="Author")
     */
    private $author;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="post")
     */
    private $comments;


    /**
     * @param Author $author
     */
    public function __construct(Author $author)
    {
        $this->author = $author;
        $this->comments = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->isActive = $active;
    }

    /**
     * @return Embedded
     */
    public function getEmbed()
    {
        if (empty($this->embed)) {
            $this->embed = new Embedded();
        }
        return $this->embed;
    }

    /**
     * @param mixed $embed
     */
    public function setEmbed($embed)
    {
        $this->embed = $embed;
    }
}