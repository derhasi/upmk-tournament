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
    protected $maxCompetingPerContestant = 2;

    /**
     * @var array
     */
    protected $heats = array();

    /**
     * @var \derhasi\upmkTournament\Combination[]
     */
    protected $combinations;

    /**
     * @var
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
        $this->contestants[] = new Contestant($name);
    }


    public function buildHeats()
    {
        $this->heats = array();

        var_dump($this->contestants);
        $this->combinations = CombinationFactory::createMultiple(array_keys($this->contestants), 4);
        print 'Combinations: ';
        var_dump($this->combinations);

        for ($heat = 1; $heat <= 3; $heat++) {
            $this->buildHeat($heat);
        }

        print 'Heats: ';
        var_dump($this->heats);

    }

    protected function buildHeat($nr)
    {

        $valuatedCombinations = array();

        foreach ($this->combinations as $key => $combination) {
            // Skip this combination if it was already marked.
            if ($combination->isMarked()) {
                continue;
            }

            $value = $this->evaluateCombination($combination);
            if ($value >= 0) {
                $valuatedCombinations[$key] = $value;
            }
        }

        asort($valuatedCombinations);

        print "Val Comb: $nr";
        var_dump($valuatedCombinations);


        $participants = array();
        $races = array();
        foreach ($valuatedCombinations as $combKey => $value) {
            $combination = $this->combinations[$combKey];
            $parts = $combination->getParts();
            // In the case no part already participates in the heat, we can use
            // this race for this heat.
            $int = array_intersect($parts, $participants);
            if  (count($int) == 0) {

                $participants += $parts;
                $races[] = $combination;
                $combination->mark();
            }
        }

        $this->heats[$nr] = $races;
    }

    /**
     * @param Combination $combination
     *
     * @return int
     *   Negative means invalid.
     */
    protected function evaluateCombination(Combination $combination) {

        $parts = $combination->getParts();

        $sum = 0;
        foreach (CombinationFactory::createMultiple($parts, 2) as $key => $duell) {
            if (isset($this->duells[$key])) {

                // In the case the duell exceeds the valid number,
                // the combination is invalid-
                if ($this->duells[$key] > $this->maxCompetingPerContestant) {
                    $sum = -1;
                    break;
                }
                else {
                    $sum += $this->duells[$key];
                }
            }
        }

        return $sum;
    }

    protected function buildRace()
    {

    }


}
