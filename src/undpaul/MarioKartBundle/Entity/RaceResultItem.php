<?php

namespace undpaul\MarioKartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RaceResultItem
 */
class RaceResultItem
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
     * @var \undpaul\MarioKartBundle\Entity\Race
     */
    private $race;

    /**
     * @var \undpaul\MarioKartBundle\Entity\User
     */
    private $user;


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
     * @return RaceResultItem
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
     * Set race
     *
     * @param \undpaul\MarioKartBundle\Entity\Race $race
     * @return RaceResultItem
     */
    public function setRace(\undpaul\MarioKartBundle\Entity\Race $race = null)
    {
        $this->race = $race;

        return $this;
    }

    /**
     * Get race
     *
     * @return \undpaul\MarioKartBundle\Entity\Race 
     */
    public function getRace()
    {
        return $this->race;
    }

    /**
     * Set user
     *
     * @param \undpaul\MarioKartBundle\Entity\User $user
     * @return RaceResultItem
     */
    public function setUser(\undpaul\MarioKartBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \undpaul\MarioKartBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
