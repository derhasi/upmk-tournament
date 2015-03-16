<?php

namespace undpaul\MarioKartBundle\Entity;


class RankingTournament extends RankingBase {

    /**
     * @var \undpaul\MarioKartBundle\Entity\Tournament
     */
    var $tournament;

    public function __construct(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRaceResultItems()
    {
        $results = array();

        foreach ($this->tournament->getRounds() as $round) {
            foreach ($round->getGames() as $game) {
                foreach ($game->getRaces() as $race) {
                    foreach ($race->getResults() as $result) {
                        $results[$result->getId()] = $result;
                    }
                }
            }
        }

        return $results;
    }
}