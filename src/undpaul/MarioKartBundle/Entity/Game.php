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
     * @param array $players
     * @param int $raceCount
     */
    protected function generateRaces($players, $raceCount = 3) {
        for ($i = 0; $i < $raceCount; $i++) {
            $race = Race::generate($this, $players);
            $this->addRace($race);
        }
    }

    /**
     * Retrieve the delta to be used for the next race.
     *
     * @return int
     */
    public function getNextDelta()
    {
        $next_delta = 0;
        foreach ($this->races as $race) {
            if ($race->getDelta() >= $next_delta) {
                $next_delta = $race->getDelta() + 1;
            }
        }
        return $next_delta;
    }

    /**
     * Get a label for the game.
     *
     * @return string
     */
    public function getFullName()
    {
        return sprintf('Game %d.%d', $this->getRound()->getDelta() + 1, $this->getDelta() + 1);
    }

    public function getOverviewData() {

        $rows = array();

        /**
         * @var Race $race
         */
        foreach ($this->getRaces() as $race) {
            /**
             * @var RaceResultItem $result
             */
            foreach ($race->getResults() as $result) {
                $player_id = $result->getPlayer()->getId();
                if (!isset($rows[$player_id])) {
                    $rows[$player_id] = new ResultOverviewRow($result->getPlayer());
                }
                $rows[$player_id]->addResult($result);
            }
        }

        return $rows;
    }

    /**
     * Retrieve players from races.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPlayers()
    {
        $players = new \Doctrine\Common\Collections\ArrayCollection();

        /**
         * @var Race $race
         * @var RaceResultItem $result
         */
        foreach ($this->races as $race)
        {
            foreach ($race->getResults() as $result)
            {
                $player = $result->getPlayer();
                if (!$players->contains($player)) {
                    $players->add($player);
                }
            }
        }

        return $players;
    }

    /**
     * Generate game for a given round.
     *
     * @param \undpaul\MarioKartBundle\Entity\Round $round
     * @param array $players
     * @param integer $number_of_races
     *
     * @return \undpaul\MarioKartBundle\Entity\Round $game
     */
    public static function generate(Round $round, array $players, $number_of_races) {

        $game = new Game();
        $game->setRound($round)
          ->setDelta($round->getNextDelta());
        $game->generateRaces($players, $number_of_races);

        return $game;
    }
}
