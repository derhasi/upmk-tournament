<?php
/**
 * Created by PhpStorm.
 * User: derhasi
 * Date: 11.02.15
 * Time: 20:55
 */

namespace derhasi\upmkTournament;


class Race implements ItemInterface
{

    const RACE_COUNT = 3;

    /**
     * @var ContestantCollection
     */
    protected $contestants;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var bool
     */
    protected $scheduled = false;

    /**
     * @var Heat
     */
    protected $heat;

    /**
     * @var bool
     */
    protected $valid = true;

    /**
     * @var
     */
    protected $results;

    protected $resultObjects;

    /**
     * @var string
     */
    protected $name;

    public function __construct(ContestantCollection $contestants) {
        $this->contestants = $contestants;
        $this->setResult(array());
    }

    public function isValid() {
        return $this->valid;
    }

    public function isScheduled() {
        return $this->scheduled;
    }

    public function schedule($name, Heat $heat) {
        $this->name = $name;
        $this->heat = $heat;
        $this->scheduled = true;
        return $this;
    }

    public function invalidate() {
        $this->valid = false;
        return $this;
    }

    public function getID() {
        if (!isset($this->id)) {

            $ids = array();
            foreach ($this->contestants as $cont) {
                $ids[] = $cont->getId();
            }
            sort($ids);
            $this->id = implode(':', $ids);
        }

        return $this->id;
    }

    /**
     * @return ContestantCollection
     */
    public function getContestants() {
        return $this->contestants;
    }

    public function getName() {
        return $this->name;
    }

    public function toArray() {

        $return = array(
          'name' => $this->getName(),
        );

        if (isset($this->heat)) {
            $return['heat'] = $this->heat->getId();
        }

        $return['results'] = $this->results;
        return $return;
    }

    /**
     * Set or initialise the results.
     * @param $results
     */
    public function setResult($results) {
        $this->results = array();

        foreach ($this->contestants->getIDs() as $id) {
            $this->results[$id] = array();

            for ($i = 0; $i < static::RACE_COUNT; $i++) {

                if (isset($results[$id][$i])) {
                    $this->results[$id][] = $results[$id][$i];
                }
                else {
                    $this->results[$id][] = 0;
                }
            }
        }

        $this->resultObjects = array();
    }

    public function buildResult(PointRules $pointRules) {
        $allRuns = array();
        // First collect all positions for the runs.
        for ($raceNo = 0; $raceNo< static::RACE_COUNT; $raceNo++) {
            $allRuns[$raceNo] = array();
            foreach ($this->results as $result) {
                $allRuns[$raceNo][] = $result[$raceNo];
            }
        }

        foreach ($this->results as $name => $runs) {
            $this->resultObjects[$name] = new RaceResult($name, $runs, $allRuns, $pointRules);
        }

        usort($this->resultObjects, function($a, $b) {
            if ($a->relSum == 0) {
                return +1;
            }

            return $a->relSum < $b->relSum;
        });
    }


    public function getResults() {
        return $this->resultObjects;
    }
}
