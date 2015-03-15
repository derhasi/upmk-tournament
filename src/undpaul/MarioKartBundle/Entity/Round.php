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
    public function setTournament(
      \undpaul\MarioKartBundle\Entity\Tournament $tournament = null
    ) {
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $games;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->games = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add games
     *
     * @param \undpaul\MarioKartBundle\Entity\Game $games
     * @return Round
     */
    public function addGame(\undpaul\MarioKartBundle\Entity\Game $games)
    {
        $this->games[] = $games;

        return $this;
    }

    /**
     * Remove games
     *
     * @param \undpaul\MarioKartBundle\Entity\Game $games
     */
    public function removeGame(\undpaul\MarioKartBundle\Entity\Game $games)
    {
        $this->games->removeElement($games);
    }

    /**
     * Get games
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGames()
    {
        return $this->games;
    }


    /**
     * Generates games for the new round.
     *
     * @param int $raceCount
     */
    public function generateGames($raceCount = 3) {

        $contestants = $this->tournament->getContestants();

        // For now we "only" generate the necessary amount of races.
        $games_count = ceil(count($contestants) / Game::MAX_PLAYERS);
        $games = array_fill(0, $games_count, 0);

        foreach ($games as $delta => $val) {
            $game = new Game();
            $game->setDelta($delta);
            $game->generateRaces($raceCount);
            $this->addGame($game);
        }

    }

}
