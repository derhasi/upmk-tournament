<?php

namespace undpaul\MarioKartBundle\Entity;

use undpaul\MarioKartBundle\Entity\RankingRowCollection;

/**
 * Base class for building rankings for a given context.
 */
abstract class RankingBase {

    /**
     * @var RankingRowCollection
     */
    protected $rows;

    /**
     * Getter for ranking rows.
     *
     * @return \undpaul\MarioKartBundle\Entity\RankingRowCollection
     */
    public function getRows()
    {
        return $this->calculate();
    }

    /**
     * Get the calculated result rows.
     *
     * @return RankingRowCollection
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
     * @return RankingRowCollection
     */
    public function recalculate()
    {
        $this->resetRows();

        foreach ($this->retrieveParticipations() as $participation) {
            $row = new RankingRow($participation);
            $this->rows->set($participation->getId(), $row);
        }

        foreach ($this->retrieveRaceResultItems() as $result) {
            $pid = $result->getParticipation()->getId();

            if (!$this->rows->containsKey($pid)) {
                // Make sure a ranking row exists for this participattion.
                $row = new RankingRow($result->getParticipation());
                $this->rows->set($pid, $row);
            }
            else {
                $row = $this->rows->get($pid);
            }
            $row->addResult($result);
        }

        // Replace rows with sorted collection.
        $this->rows = $this->rows->sort();

        return $this->rows;
    }

    /**
     * Helper to initalise and reset rows.
     */
    protected function resetRows() {
        $this->rows = new RankingRowCollection();
    }

    /**
     * Return participations to be added before retrieving rows from results.
     */
    protected function retrieveParticipations()
    {
        return array();
    }

    /**
     * Return result entries valid for the given ranking.
     *
     * @return RaceResultItem[]
     */
    abstract protected function retrieveRaceResultItems();

}