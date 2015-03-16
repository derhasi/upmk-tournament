<?php

namespace undpaul\MarioKartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Game
 */
class Game
{
    /**
     * Maximum number of players for a game.
     */
    const MAX_PLAYERS = 4;

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
    private $players;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $races;

    /**
     * @var \undpaul\MarioKartBundle\Entity\Round
     */
    private $round;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->races = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Set round
     *
     * @param \undpaul\MarioKartBundle\Entity\Round $round
     * @return Game
     */
    public function setRound(
      \undpaul\MarioKartBundle\Entity\Round $round = null
    ) {
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
     * Generate races for this game.
     *
     * @param int $raceCount
     */
    protected function generateRaces($raceCount = 3) {

        for ($i = 0; $i < $raceCount; $i++) {
            $race = new Race();
            $race->setGame($this);
            $race->setDelta($i);
            $this->addRace($race);
        }
    }

    /**
     * Add players
     *
     * @param \undpaul\MarioKartBundle\Entity\Player $players
     * @return Game
     */
    public function addPlayer(\undpaul\MarioKartBundle\Entity\Player $players)
    {
        $this->players[] = $players;

        return $this;
    }

    /**
     * Remove players
     *
     * @param \undpaul\MarioKartBundle\Entity\Player $players
     */
    public function removePlayer(\undpaul\MarioKartBundle\Entity\Player $players)
    {
        $this->players->removeElement($players);
    }

    /**
     * Get players
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * Generate game for a given round.
     *
     * @param \undpaul\MarioKartBundle\Entity\Round $round
     * @param $number_of_races
     *
     * @return \undpaul\MarioKartBundle\Entity\Round $game
     */
    public static function generate(Round $round, array $players, $number_of_races) {

        $game = new Game();
        $game->setRound($round)
          ->setDelta($round->getNextDelta());
        foreach ($players as $player) {
            $game->addPlayer($player);
        }

        $game->generateRaces($number_of_races);

        return $game;
    }
}
