<?php

namespace undpaul\MarioKartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tournament
 */
class Tournament
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $rounds;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $participations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rounds = new \Doctrine\Common\Collections\ArrayCollection();
        $this->participations = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return Tournament
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
     * Add rounds
     *
     * @param \undpaul\MarioKartBundle\Entity\Round $rounds
     * @return Tournament
     */
    public function addRound(\undpaul\MarioKartBundle\Entity\Round $rounds)
    {
        $this->rounds[] = $rounds;

        return $this;
    }

    /**
     * Remove rounds
     *
     * @param \undpaul\MarioKartBundle\Entity\Round $rounds
     */
    public function removeRound(\undpaul\MarioKartBundle\Entity\Round $rounds)
    {
        $this->rounds->removeElement($rounds);
    }

    /**
     * Get rounds
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRounds()
    {
        return $this->rounds;
    }

    /**
     * Check if the tournament already has started.
     *
     * @return bool
     */
    public function isStarted()
    {
        return count($this->rounds) > 0;
    }

    /**
     * Retrieve the delta to be used for the next tournament round.
     *
     * @return int
     */
    public function getNextDelta()
    {
        $next_delta = 0;

        /**
         * @var Round $round
         */
        foreach ($this->rounds as $round) {
            if ($round->getDelta() >= $next_delta) {
                $next_delta = $round->getDelta() + 1;
            }
        }
        return $next_delta;
    }

    /**
     * Add participations
     *
     * @param \undpaul\MarioKartBundle\Entity\Participation $participations
     * @return Tournament
     */
    public function addParticipation(\undpaul\MarioKartBundle\Entity\Participation $participations)
    {
        $this->participations[] = $participations;

        return $this;
    }

    /**
     * Remove participations
     *
     * @param \undpaul\MarioKartBundle\Entity\Participation $participations
     */
    public function removeParticipation(\undpaul\MarioKartBundle\Entity\Participation $participations)
    {
        $this->participations->removeElement($participations);
    }

    /**
     * Get participations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParticipations()
    {
        return $this->participations;
    }

    public function getPlayers()
    {
        $players = new \Doctrine\Common\Collections\ArrayCollection();

        /* @var Participation $participation */
        foreach ($this->participations as $participation) {
            $players->add($participation->getPlayer());
        }
        return $players;
    }
}
