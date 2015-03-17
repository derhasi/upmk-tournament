<?php

namespace undpaul\MarioKartBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Participation
 */
class Participation
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $results;

    /**
     * @var \undpaul\MarioKartBundle\Entity\Tournament
     */
    private $tournament;

    /**
     * @var \undpaul\MarioKartBundle\Entity\Player
     */
    private $player;

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
     * Add results
     *
     * @param \undpaul\MarioKartBundle\Entity\RaceResultItem $results
     * @return Participation
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
     * Set tournament
     *
     * @param \undpaul\MarioKartBundle\Entity\Tournament $tournament
     * @return Participation
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

    /**
     * Set player
     *
     * @param \undpaul\MarioKartBundle\Entity\Player $player
     * @return Participation
     */
    public function setPlayer(\undpaul\MarioKartBundle\Entity\Player $player = null)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return \undpaul\MarioKartBundle\Entity\Player 
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Get races player participated in.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getRaces()
    {
        $races = array();
        foreach ($this->getResults() as $result) {
            $races[$result->getRace()->getId()] = $result->getRace();
        }
        return new ArrayCollection($races);
    }

    /**
     * String representation of the object.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->player->getName();
    }

    public function getDuellCount(Participation $opponent)
    {
        $count = 0;
        /** @var Race $race */
        foreach ($this->getRaces() as $race) {
            if ($race->hasParticipation($opponent)) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * @return Duell[]
     */
    public function getDuells()
    {
        $return = array();
        foreach ($this->tournament->getDuells() as $duell) {
            if ($duell->hasParticipaton($this)) {
                $return[] = $duell;
            }
        }
        return $return;
    }

    /**
     * @return DuellResult[]
     */
    protected function getDuellResults()
    {
        $return = array();
        foreach ($this->getDuells() as $duell) {
            $return[] = new DuellResult($duell, $this);
        }
        return $return;
    }

    /**
     * @return DuellRankingRow[]
     */
    public function getDuellRankings()
    {
        $return = array();

        foreach ($this->tournament->getParticipations() as $participation) {
            if ($participation->getId() != $this->getId()) {
                $id = $participation->getId();
                $return[$id] = new DuellRankingRow($participation);
            }
        }

        $results = $this->getDuellResults();
        foreach ($results as $result) {
            $return[$result->opponent->getId()]->addDuellResult($result);
        }
        return $return;
    }
}
