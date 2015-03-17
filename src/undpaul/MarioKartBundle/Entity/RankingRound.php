<?php

namespace undpaul\MarioKartBundle\Entity;


class RankingRound extends RankingBase {

    /**
     * @var \undpaul\MarioKartBundle\Entity\Round
     */
    var $round;

    public function __construct(Round $round)
    {
        $this->round = $round;
    }

    /**
     * {@inheritdoc}
     */
    protected function retrieveRaceResultItems()
    {
        $results = array();
        foreach ($this->round->getGames() as $game) {
            foreach ($game->getRaces() as $race) {
                foreach ($race->getResults() as $result) {
                    $results[$result->getId()] = $result;
                }
            }
        }

        return $results;
    }
}