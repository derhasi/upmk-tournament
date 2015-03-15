<?php

namespace undpaul\MarioKartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use undpaul\MarioKartBundle\Entity\Tournament;

/**
 * Round
 */
class Round
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $delta;

    /**
     * @var \undpaul\MarioKartBundle\Entity\Tournament
     */
    private $tournament;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Round
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set delta
     *
     * @param integer $delta
     * @return Round
     */
    public function setDelta($delta)
    {
        $this->delta = $delta;

        return $this;
    }

    /**
     * Get delta
     *
     * @return integer 
     */
    public function getDelta()
    {
        return $this->delta;
    }

    /**
     * Set tournament
     *
     * @param \undpaul\MarioKartBundle\Entity\Tournament $tournament
     * @return Round
     */
    public function setTournament(\undpaul\MarioKartBundle\Entity\Tournament $tournament = null)
    {
        $this->tournament = $tournament;

        return $this;
    }

    /**
     * Get tournament
     *
     * @return \undpaul\MarioKartBundle\Entity\Tournament 
     */
    public function getTournament()
    {
        return $this->tournament;
    }
}
