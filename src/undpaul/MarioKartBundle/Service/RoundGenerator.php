<?php

namespace undpaul\MarioKartBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use undpaul\MarioKartBundle\Entity\Game;
use undpaul\MarioKartBundle\Entity\Participation;
use undpaul\MarioKartBundle\Entity\RankingRow;
use undpaul\MarioKartBundle\Entity\RankingRowCollection;
use undpaul\MarioKartBundle\Entity\RankingTournament;
use undpaul\MarioKartBundle\Entity\Round;
use undpaul\MarioKartBundle\Entity\Tournament;

class RoundGenerator {

    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    var $doctrine;

    /**
     * @var int
     */
    var $duellCountLimit = 0;

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

        $allParticipations = $tournament->getParticipations();

        // For now we "only" generate the games the simple way. The algorithm
        // for swiss style tournaments will follow later.
        $gamesCount = $this->calculateGamesCount(count($allParticipations));

        $ranking = new RankingTournament($tournament);
        $collection = $ranking->getRows();

        // Counter to avoid unlimited loop.
        $securityCounter = 0;

        while (count($gamesCount) && $securityCounter <= 1000) {
            $securityCounter++;

            $maxPlayers = max($gamesCount);
            $rows = $this->getNextRowsForParticipants($maxPlayers, $collection);

            $count = count($rows);
            $game_key = array_search($count, $gamesCount);

            if ($game_key !== FALSE) {

                // If the rows collection was successfull, we remove those from
                // the collection, so the next run, can find relevant ones.
                // In addition we need to extract the Participation object from
                // those.
                $gameParticipants = array();
                foreach ($rows as $row_key => $row) {
                    $collection->remove($row_key);
                    $gameParticipants[] = $row->getParticipation();
                }

                $game = Game::generate($round, $gameParticipants, $number_of_races);
                $round->addGame($game);
                unset($gamesCount[$game_key]);
            }
            else {
                // In the case the search was not successfull, we try to solve
                // this with increasing the duell limit.
                $this->duellCountLimit++;
            }
        }

        // We try to avoid unlimited loops, but we need to throw an exception
        // here as we need to know something is wrong.
        if ($securityCounter > 1000) {
            throw new \Exception('Bad looooop!');
        }

        return $round;
    }

    /**
     * Helper to retrieve relevant opponents "Swiss style".
     *
     * "Swiss style" first allways matches highest ranked participants against
     * each other, but only those that have not duelled before.
     *
     * @param $maxPlayers
     * @param \undpaul\MarioKartBundle\Entity\RankingRowCollection $collection
     * @return array
     */
    protected function getNextRowsForParticipants($maxPlayers, RankingRowCollection $collection)
    {
        /* @var RankingRow $row */
        /* @var RankingRow $other */
        $row = $collection->first();

        $rows = array($collection->key() => $row);
        while (count($rows) < $maxPlayers && $collection->next() !== false) {

            $row = $collection->current();

            // Check if any participant exceeds the current duell limit.
            foreach ($rows as $other) {
                $duellCount = $row->getParticipation()
                  ->getDuellCount($other->getParticipation());

                // Skip if this one had enough duells with the other.
                if ($duellCount > $this->duellCountLimit) {
                    continue 2;
                }
            }

            // If it survived all participants, it can be added.
            $rows[$collection->key()] = $row;
        }

        return $rows;
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