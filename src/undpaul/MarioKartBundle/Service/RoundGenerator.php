<?php

namespace undpaul\MarioKartBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use undpaul\MarioKartBundle\Entity\Round;
use undpaul\MarioKartBundle\Entity\Tournament;

class RoundGenerator {

    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    var $doctrine;

    /**
     * Constructor.
     *
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $registry
     */
    public function __construct(Registry $registry) {
        $this->doctrine = $registry;
    }

    /**
     * Generate a new round for the tournament.
     *
     * @param \undpaul\MarioKartBundle\Entity\Tournament $tournament
     * @param integer $number_of_races
     *
     * @return \undpaul\MarioKartBundle\Entity\Round
     */
    public function generateNewRound(Tournament $tournament, $number_of_races)
    {

        $round = new Round();
        $round->setTournament($tournament)
          ->setDelta($tournament->getNextDelta());

        $ps = $tournament->getParticipations();

        // For now we "only" generate the games the simple way. The algorithm
        // for swiss style tournaments will follow later.
        $games_count = ceil(count($ps) / Game::MAX_PLAYERS);
        $games = array_fill(0, $games_count, 0);

        $offset = 0;

        foreach ($games as $delta => $val) {
            $gamePs = $ps->slice($offset, Game::MAX_PLAYERS);
            $game = Game::generate($this, $gamePs, $number_of_races);
            $round->addGame($game);
            $offset += Game::MAX_PLAYERS;
        }

        return $round;
    }

}