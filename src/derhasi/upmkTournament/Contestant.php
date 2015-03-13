<?php

namespace derhasi\upmkTournament;

class Contestant implements ItemInterface
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Race[]
     */
    public $heatRaces = array();

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->name;
    }

    /**
     * @param \derhasi\upmkTournament\Race $race
     * @throws \Exception
     */
    public function addHeatRace(Race $race) {
        if (!$race->isScheduled()) {
            throw new \Exception('Race is not scheduled yet');
        }

        $this->heatRaces[$race->getID()] = $race;
    }

}
