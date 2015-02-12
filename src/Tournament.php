<?php

namespace derhasi\upmkTournament;

class Tournament
{

    /**
     * @var ContestantCollection
     */
    protected $contestants;

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
     * @var Heat[]
     */
    protected $heats = array();

    /**
     * @var \derhasi\upmkTournament\RaceCollection
     */
    protected $races;

    /**
     * @var DuellCollection
     */
    protected $duells;

    /**
     * @param string[] $names
     */
    public function __construct($names = array())
    {
        $this->contestants = new ContestantCollection($names);
    }

    /**
     * @return \derhasi\upmkTournament\Heat[]
     */
    public function getHeats() {
        // @todo: cache
        $this->buildHeats();
        return $this->heats;
    }

    /**
     * @return \derhasi\upmkTournament\ContestantCollection
     */
    public function getContestants() {
        return $this->contestants;
    }

    protected function buildHeats()
    {
        $this->heats = array();
        $this->duells = new DuellCollection($this->contestants);
        $this->races = new RaceCollection($this->contestants, 4);
        for ($heat = 1; $heat <= $this->heatCount; $heat++) {
            $this->buildHeat($heat);
        }
    }

    protected function buildHeat($heatNo)
    {
        // Before we can add races to the heat, we first have to check what
        // races would be valid.
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

        // Finally build the heat.
        $heat = new Heat($heatNo);
        foreach ($valuatedRaces as $key => $value) {
            $race = $this->races[$key];

            if ($heat->validRace($race)) {
                $heat->addRace($race);
                $this->setDuellClashesForRace($race);
                $this->setParticipationForRace($race);
            }
        }

        $this->heats[$heatNo] = $heat;
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
        return $this->duells->getDuellsForContestants($con);
    }

    /**
     * @param Race $race
     */
    protected function setDuellClashesForRace(Race $race) {
        $duells = $this->getDuellsFromRace($race);
        foreach ($duells as $duell) {
            $duell->clash($race);
        }
    }

    /**
     * @param Race $race
     */
    protected function setParticipationForRace(Race $race) {
        foreach ($race->getContestants() as $contestant) {
            $contestant->addHeatRace($race);
        }
    }
}
