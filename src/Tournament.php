<?php

namespace derhasi\upmkTournament;

class Tournament
{

    public $results = array();

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
     * @var PointRules
     */
    protected $pointRules;

    /**
     * @param Data $names
     */
    public function __construct(Data $data)
    {
        $this->data = $data;
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

    protected function buildHeats()
    {
        // Build missing races for the given contestants and race size.
        $this->races->generateRaces($this->contestants, 4);

        $heatNo = 0;
        do {
            $heatNo++;
            $heat = $this->buildHeat($heatNo);
            if (!empty($heat->races)) {
                $this->heats[$heat->getId()] = $heat;
            }
        } while(!empty($heat->races) && $this->nextHeatNeeded());
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
                $this->addRaceToHeat($race, $heat);
            }
        }

        return $heat;
    }

    protected function addRaceToHeat(Race $race, Heat $heat) {
        $heat->addRace($race);
        $this->setDuellClashesForRace($race);
        $this->setParticipationForRace($race);
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

    /**
     * Write heat races to yaml.
     */
    public function writeHeatRacesToData() {
        $return = array();
        foreach ($this->races as $race) {
            /* @var Race $race */
            if ($race->isScheduled()) {
                $return[$race->getID()] = $race->toArray();
            }
        }

        uasort($return, function($a, $b) {
            return $a['name'] > $b['name'] ;
        });

        $this->data->writeHeatRaces($return);
    }

    protected function initConfig() {
        $data = $this->data->loadConfig();
        $this->contestants = new ContestantCollection($data['contestants']);

        if (isset($data['maxHeatRacesPerContestant'])) {
            $this->maxHeatRacesPerContestant = $data['maxHeatRacesPerContestant'];
        }
        if (isset($data['maxDuellCount'])) {
            $this->maxDuellCount = $data['maxDuellCount'];
        }

        $this->pointRules = new PointRules($data['pointsAbsolute'], $data['pointsRelative']) ;
    }

    public function init() {
        $this->initConfig();
        $this->duells = new DuellCollection($this->contestants);
        $this->heats = new HeatsCollection();
        $this->races = new RaceCollection();

        $raw_races = $this->data->loadHeatRaces();

        // If we got no race data
        if (!empty($raw_races)) {
            $this->buildFromRawRaces($raw_races);
            $this->buildHeats();
            $this->writeHeatRacesToData();
        }
        // Build fresh heat information from scratch with given contestants.
        else {
            $this->buildHeats();
            $this->writeHeatRacesToData();
        }
    }

    protected function buildFromRawRaces($raw_races) {


        foreach ($raw_races as $raw) {

            // Add or get existing heat.
            $heatNo = $raw['heat'];
            $this->heats->add(new Heat($heatNo));
            $heat = $this->heats->getById($heatNo);

            $contestantNames = array_keys($raw['results']);

            $name = $raw['name'];
            $contestants = $this->contestants->getByNames($contestantNames);

            // Build race object and register it to the collection.
            $race = new Race($contestants);
            $this->races->add($race);
            $race = $this->races->getItem($race);
            $race->schedule($name, $heat);
            $race->setResult($raw['results']);

            // Make sure it
            $this->addRaceToHeat($race, $heat);
        }
    }


    public function buildResults() {

        $results = array();

        foreach ($this->races as $race) {
            /**
             * @var Race $race
             */

            if (!$race->isScheduled()) {
                continue;
            }

            $race->buildResult($this->pointRules);

            foreach ($race->getResults() as $result) {
                /**
                 * @var RaceResult $result
                 */

                if (!isset($results[$result->name])) {
                    $results[$result->name] = array(
                      'name' => $result->name,
                      'races' => 0,
                      'scheduled' => 0,
                      'points' => 0,
                      'absPoints' => 0,
                    );
                }

                $results[$result->name]['scheduled']++;
                $results[$result->name]['races'] += $result->relSum > 0;
                $results[$result->name]['points'] += $result->relSum;
                $results[$result->name]['absPoints'] += $result->absSum;
            }
        }

        usort($results, function($a, $b) {
            if ($a['races'] != $b['races']) {
                return $a['races'] > $b['races'];
            }
            elseif ($a['points'] != $b['points']) {
                return $a['points'] > $b['points'];
            }
            else {
                return $a['absPoints'] > $b['absPoints'];
            };
        });

        $pos = 1;
        foreach ($results as &$item) {
            $item['pos'] = $pos++;
        }

        $this->results = $results;
    }


}
