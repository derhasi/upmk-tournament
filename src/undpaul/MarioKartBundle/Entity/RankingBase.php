<?php

namespace undpaul\MarioKartBundle\Entity;

/**
 * Base class for building rankings for a given context.
 */
abstract class RankingBase {

    /**
     * @var ResultOverviewRow[]
     */
    protected $rows;

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

        foreach ($this->getRaceResultItems() as $result) {
            $pid = $result->getParticipation()->getId();
            if (!isset($this->rows[$pid])) {
                $this->rows[$pid] = new ResultOverviewRow($result->getParticipation());
            }
            $this->rows[$pid]->addResult($result);
        }

        ResultOverviewRow::sort($this->rows);

        return $this->rows;
    }

    /**
     * Return result entries valid for the given ranking.
     *
     * @return RaceResultItem[]
     */
    abstract protected function getRaceResultItems();

}