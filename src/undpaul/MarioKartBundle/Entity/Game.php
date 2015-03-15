<?php

namespace undpaul\MarioKartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 */
class Game
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $delta;

    /**
     * @var \undpaul\MarioKartBundle\Entity\Round
     */
    private $round;


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
     * Set delta
     *
     * @param integer $delta
     * @return Game
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
     * Set round
     *
     * @param \undpaul\MarioKartBundle\Entity\Round $round
     * @return Game
     */
    public function setRound(\undpaul\MarioKartBundle\Entity\Round $round = null)
    {
        $this->round = $round;

        return $this;
    }

    /**
     * Get round
     *
     * @return \undpaul\MarioKartBundle\Entity\Round 
     */
    public function getRound()
    {
        return $this->round;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $races;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->races = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add races
     *
     * @param \undpaul\MarioKartBundle\Entity\Race $races
     * @return Game
     */
    public function addRace(\undpaul\MarioKartBundle\Entity\Race $races)
    {
        $this->races[] = $races;

        return $this;
    }

    /**
     * Remove races
     *
     * @param \undpaul\MarioKartBundle\Entity\Race $races
     */
    public function removeRace(\undpaul\MarioKartBundle\Entity\Race $races)
    {
        $this->races->removeElement($races);
    }

    /**
     * Get races
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRaces()
    {
        return $this->races;
    }
}
