<?php

namespace derhasi\upmkTournament;


class Heat implements ItemInterface {

  protected $heatNo;

  /**
   * @var Race[]
   */
  public $races = array();

  /**
   * @var ContestantCollection
   */
  public $participants;

  public function __construct($heatNo) {
    $this->heatNo = $heatNo;
    $this->participants = new ContestantCollection();
  }

  public function getId() {
    return $this->heatNo;
  }

  public function addRace(Race $race) {
    $this->participants->addMultiple($race->getContestants());
    $raceNumber =  count($this->races) + 1;

    $race->schedule(sprintf('Race %s.%s', $this->getId(), $raceNumber), $this);
    $this->races[$raceNumber] = $race;
  }

  /**
   * Checks if we can add the race to the current heat.
   *
   * @param \derhasi\upmkTournament\Race $race
   * @return bool
   */
  public function validRace(Race $race) {
    $int = array_intersect($race->getContestants()->getIDs(), $this->participants->getIDs());
    return count($int) == 0;
  }

}