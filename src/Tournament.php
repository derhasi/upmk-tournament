<?php

namespace derhasi\upmkTournament;

class Tournament
{

    /**
     * @var Data
     */
    protected $data;

    /**
     * @var ContestantCollection
     */
    protected $contestants;

    /**
     * @var int
     */
    protected $maxHeatRacesPerContestant = 4;

    /**
     * @var int
     */
    protected $koRounds = 2;

    /**
     * @var int
     */
    protected $maxDuellCount = 3;

    /**
     * @var HeatsCollection
     */
    protected $heats;

    /**
     * @var \derhasi\upmkTournament\RaceCollection
     */
    protected $races;

    /**
     * @var DuellCollection
     */
    protected $duells;

    /**
     * @param Data $names
     */
    public function __construct(Data $data)
    {
        $this->data = $data;
        $names = $data->loadContestants();
        $this->contestants = new ContestantCollection($names);
    }

    /**
     * @return \derhasi\upmkTournament\Heat[]
     */
    public function getHeats() {
        return $this->heats;
    }

    /**
     * @return \derhasi\upmkTournament\ContestantCollection
     */
    public function getContestants() {
        return $this->contestants;
    }

    public function buildHeats()
    {
        $this->heats = new HeatsCollection();
        $this->duells = new DuellCollection($this->contestants);
        $this->races = RaceCollection::generateRaces($this->contestants, 4);

        $heatNo = 0;
        do {
            $heatNo++;
            $heat = $this->buildHeat($heatNo);
            if (!empty($heat->races)) {
                $this->heats[$heat->getId()] = $heat;
            }
        } while(!empty($heat->races) && $this->nextHeatNeeded());

        $this->data->writeHeats($this->heats);
    }

    protected function nextHeatNeeded() {
        $amount = $this->amountOfHeatRaces();
        if ($amount['min'] != $amount['max']) {
            return true;
        }
        elseif ($amount['min'] < $this->maxHeatRacesPerContestant) {
            return true;
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

            $this->validateHeatRace($race);

            // If the race is still valid, we calculate a sum for duell matching.
            if ($race->isValid()) {
                $sum = $this->evaluateHeatRaceSum($race);
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

        return $heat;
    }

    protected function validateHeatRace(Race $race) {
        // First check if any participant has reached the max race count.
        foreach ($race->getContestants() as $contestant) {
            if (count($contestant->heatRaces) >= $this->maxHeatRacesPerContestant) {
                $race->invalidate();
                return;
            }
        }

        // Then check if the max duell count has been reached.
        $duells = $this->getDuellsFromRace($race);
        foreach ($duells as $duell) {
            if ($duell->getCount() > $this->maxDuellCount) {
                $race->invalidate();
                return;
            }
        }
    }

    /**
     * @param Race $race
     *
     * @return int
     *   Negative means invalid.
     */
    protected function evaluateHeatRaceSum(Race $race) {
        $duells = $this->getDuellsFromRace($race);
        $sum = 0;
        foreach ($duells as $duell) {
            $sum += $duell->getCount();
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

    protected function amountOfHeatRaces() {
        $counts = array();
        foreach ($this->contestants as $contestant) {
            $counts[] = count($contestant->heatRaces);
        }

        return array('min' => min($counts), 'max' => max($counts));
    }
}
