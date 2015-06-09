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
 * Class Embedded
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 *
 * @ORM\Embeddable()
 */
class Embedded
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true, options={ "default"="NOW()" })
     */
    private $date;

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }
}