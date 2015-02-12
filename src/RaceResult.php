<?php
/**
 * Created by PhpStorm.
 * User: derhasi
 * Date: 12.02.15
 * Time: 17:37
 */

namespace derhasi\upmkTournament;


class RaceResult {

  /**
   * @var string
   */
  public $name;
  public $absPos;
  public $relPos;
  public $absPts;
  public $resPts;
  public $absSum;
  public $relSum;

  public function __construct($name, $runs, $allRuns, PointRules $pointRules) {

    $this->name = $name;
    $this->absPos = $runs;
    $this->relPos = $pointRules->getRelativePositions($runs, $allRuns);

    $this->absPts = $pointRules->getAbsolutePoints($this->absPos);
    $this->relPts  = $pointRules->getRelativePoints($this->relPos);
    $this->absSum = array_sum($this->absPts);
    $this->relSum = array_sum($this->relPts );
  }


}