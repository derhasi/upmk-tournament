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

    /**
     * Get a label for the game.
     *
     * @return string
     */
    public function getFullName()
    {
        return sprintf('Race %d.%d.%d',
          $this->getGame()->getDelta() + 1,
          $this->getGame()->getDelta() + 1,
          $this->getDelta() + 1
        );
    }

    /**
     * Check if player participated/participates in this race.
     *
     * @param \undpaul\MarioKartBundle\Entity\Participation $participation
     *
     * @return bool
     */
    public function hasParticipation(Participation $participation)
    {
        /** @var RaceResultItem $result */
        foreach ($this->getResults() as $result) {
            if ($result->getParticipation()->getId() == $participation->getId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Generate result items for the given race.
     *
     * @param array $participations
     */
    protected function generateResultItems($participations)
    {
        foreach ($participations as $p) {
            $item = RaceResultItem::generate($this, $p);
            $this->addResult($item);
        }
    }

    /**
     * Generates a fresh race with the given players.
     *
     * @param \undpaul\MarioKartBundle\Entity\Game $game
     * @param array $participations
     *
     * @return \undpaul\MarioKartBundle\Entity\Race
     */
    public static function generate(Game $game, array $participations)
    {
        $race = new Race();
        $race->setGame($game)
            ->setDelta($game->getNextDelta());
        $race->generateResultItems($participations);

        return $race;
    }
}
