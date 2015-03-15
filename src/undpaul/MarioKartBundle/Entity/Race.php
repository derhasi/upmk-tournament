<?php

namespace undpaul\MarioKartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Race
 */
class Race
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $results;

    /**
     * @var \undpaul\MarioKartBundle\Entity\Game
     */
    private $game;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->results = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set delta
     *
     * @param integer $delta
     * @return Race
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
     * Add results
     *
     * @param \undpaul\MarioKartBundle\Entity\RaceResultItem $results
     * @return Race
     */
    public function addResult(\undpaul\MarioKartBundle\Entity\RaceResultItem $results)
    {
        $this->results[] = $results;

        return $this;
    }

    /**
     * Remove results
     *
     * @param \undpaul\MarioKartBundle\Entity\RaceResultItem $results
     */
    public function removeResult(\undpaul\MarioKartBundle\Entity\RaceResultItem $results)
    {
        $this->results->removeElement($results);
    }

    /**
     * Get results
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Set game
     *
     * @param \undpaul\MarioKartBundle\Entity\Game $game
     * @return Race
     */
    public function setGame(\undpaul\MarioKartBundle\Entity\Game $game = null)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return \undpaul\MarioKartBundle\Entity\Game 
     */
    public function getGame()
    {
        return $this->game;
    }
}
