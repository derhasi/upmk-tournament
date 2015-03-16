<?php

namespace undpaul\MarioKartBundle\Entity;


class RankingGame {

    /**
     * @var \undpaul\MarioKartBundle\Entity\Game
     */
    protected $game;

    /**
     * @var ResultOverviewRow[]
     */
    protected $rows;

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
     * Get the calculated result rows.
     *
     * @return ResultOverviewRow[]
     */
    public function calculate()
    {
        if (!empty($this->rows)) {
            return $this->rows;
        }

        return $this->recalculate();
    }

    /**
     * Recalculate the result rows for the given game.
     *
     * @return array
     */
    public function recalculate()
    {
        $this->rows = array();

        /**
         * @var Race $race
         */
        foreach ($this->game->getRaces() as $race) {
            /**
             * @var RaceResultItem $result
             */
            foreach ($race->getResults() as $result) {
                $pid = $result->getParticipation()->getId();
                if (!isset($this->rows[$pid])) {
                    $this->rows[$pid] = new ResultOverviewRow($result->getParticipation());
                }
                $this->rows[$pid]->addResult($result);
            }
        }

        ResultOverviewRow::sort($this->rows);

        return $this->rows;
    }



}