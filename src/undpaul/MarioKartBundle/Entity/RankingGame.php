<?php

namespace undpaul\MarioKartBundle\Entity;


class RankingGame extends RankingBase {

    /**
     * @var \undpaul\MarioKartBundle\Entity\Game
     */
    protected $game;

    /**
     * Constructor.
     *
     * @param \undpaul\MarioKartBundle\Entity\Game $game
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
    }


    /**
     * {@inheritdoc}
     */
    protected function retrieveRaceResultItems()
    {
        $results = array();
        foreach ($this->game->getRaces() as $race) {
            foreach ($race->getResults() as $result) {
                $results[$result->getId()] = $result;
            }
        }
        return $results;
    }


}