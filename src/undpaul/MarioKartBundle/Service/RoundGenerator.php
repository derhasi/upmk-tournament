<?php

namespace undpaul\MarioKartBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use undpaul\MarioKartBundle\Entity\Game;
use undpaul\MarioKartBundle\Entity\RankingTournament;
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
        $gamesCount = $this->calculateGamesCount(count($ps));

        $ranking = new RankingTournament($tournament);
        $rows = $ranking->calculate();

        $offset = 0;
        foreach ($gamesCount as $playerCount) {
            $gamePs = $ps->slice($offset, $playerCount);
            $game = Game::generate($round, $gamePs, $number_of_races);
            $round->addGame($game);
            $offset += $playerCount;
        }

        return $round;
    }



    /**
     * Calculate the games count with relevant number of players for each game.
     *
     * @param integer $playerCount
     *
     * @return array
     */
    protected function calculateGamesCount($playerCount)
    {
        $games_count = ceil($playerCount / Game::MAX_PLAYERS);
        $games = array_fill(0, $games_count, 0);

        for ($i = 0; $i < $playerCount; $i++) {
            $key = key($games);
            $games[$key]++;

            if (next($games) === false) {
                reset($games);
            }
        }

        return $games;
    }

}