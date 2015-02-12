<?php

namespace derhasi\upmkTournament;

class Tournament
{

    /**
     * @var Contestant[]
     */
    protected $contestants = array();

    /**
     * @var int
     */
    protected $heatCount = 3;

    /**
     * @var int
     */
    protected $koRounds = 2;

    /**
     * @var int
     */
    protected $maxDuellCount = 2;

    /**
     * @var array
     */
    protected $heats = array();

    /**
     * @var \derhasi\upmkTournament\Race[]
     */
    protected $races;

    /**
     * @var Duell[]
     */
    protected $duells;

    /**
     * @param string[] $names
     */
    public function __construct($names = array())
    {
        foreach ($names as $name) {
            $this->addContestant($name);
        }
    }

    public function addContestant($name)
    {
        $id = count($this->contestants) + 1;
        $this->contestants[$id] = new Contestant($name, $id);
    }

    public function getHeats() {
        // @todo: cache
        $this->buildHeats();
        return $this->heats;
    }

    protected function buildHeats()
    {
        $this->heats = array();
        $this->duells = DuellFactory::createSingletons($this->contestants);
        $this->races = RaceFactory::create($this->contestants, 4);
        for ($heat = 1; $heat <= $this->heatCount; $heat++) {
            $this->buildHeat($heat);
        }
    }

    protected function buildHeat($heatNo)
    {

        $valuatedRaces = array();

        // First we check, what races can be
        foreach ($this->races as $key => $race) {
            // Skip this race if it is already scheduled.
            if ($race->isScheduled() || !$race->isValid()) {
                continue;
            }

            // Evaluate the sum. If the race is still a vali option after that
            // we add it to the possible races.
            $sum = $this->evaluateRaceSum($race);
            if ($race->isValid()) {
                $valuatedRaces[$key] = $sum;
            }
        }

        // Sort ids by smallest sum.
        asort($valuatedRaces);

        $participants = array();
        $races = array();
        $raceCounter = 1;
        foreach ($valuatedRaces as $key => $value) {
            $race = $this->races[$key];
            $ids = $this->getContestandIdsFromRace($race);
            // In the case no part already participates in the heat, we can use
            // this race for this heat.
            $int = array_intersect($ids, $participants);
            if  (count($int) == 0) {

                $participants = array_merge($participants, $ids);
                $race->schedule(sprintf('Race %s.%s', $heatNo, $raceCounter), $heatNo);
                $this->setDuellClashesForRace($race);
                $races[] = $race;
                $raceCounter++;
            }
        }

        $this->heats[$heatNo] = $races;
    }

    /**
     * @param Race $race
     *
     * @return int
     *   Negative means invalid.
     */
    protected function evaluateRaceSum(Race $race) {

        $duells = $this->getDuellsFromRace($race);

        $sum = 0;
        foreach ($duells as $duell) {
            $sum += $duell->getCount();
            if ($duell->getCount() > $this->maxDuellCount) {
                $race->invalidate();
            }
        }

        return $sum;
    }

    /**
     * @param Race $race
     *
     * @return Duell[]
     */
    protected function getDuellsFromRace(Race $race) {
        $con = $race->getContestants();
        return DuellFactory::createSingletons($con);
    }

    /**
     * @param Race $race
     */
    protected function setDuellClashesForRace(Race $race) {
        $duells = $this->getDuellsFromRace($race);
        foreach ($duells as $duell) {
            $duell->clash($race->getName());
        }
    }

    /**
     * @param Race $race
     *
     * @return string[]
     */
    protected function getContestandIdsFromRace(Race $race) {
        $cons = $race->getContestants();
        $return = array();
        foreach ($cons as $c) {
            $return[] = $c->getId();
        }
        return $return;
    }
}
