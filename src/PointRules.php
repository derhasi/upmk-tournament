<?php
/**
 * Created by PhpStorm.
 * User: derhasi
 * Date: 12.02.15
 * Time: 17:09
 */

namespace derhasi\upmkTournament;


class PointRules {

  /**
   * @var array
   */
  protected $absolute;

  /**
   * @var
   */
  protected $relative;

  /**
   * @param array $pointsAbsolute
   * @param array $pointsRelative
   */
  public function __construct($pointsAbsolute, $pointsRelative) {
    $this->absolute = $pointsAbsolute;
    $this->relative = $pointsRelative;
  }

  public function getAbsolutePoint($position) {
    if (isset($this->absolute[$position])) {
      return $this->absolute[$position];
    }
    return 0;
  }

  public function getRelativePoint($relativePosition) {
    if (isset($this->relative[$relativePosition])) {
      return $this->relative[$relativePosition];
    }
    return 0;
  }

  public function getRelativePosition($position, array $allPositions) {
    $relativePos = 1;
    foreach ($allPositions as $otherPos) {
      $relativePos += ($otherPos && $otherPos < $position);
    }
    return $relativePos;
  }

  /**
   * @param array $myRuns
   * @param array $allRuns
   * @return array
   */
  public function getRelativePositions(array $myRuns, array $allRuns) {
    $return = array();
    foreach ($myRuns as $i => $run) {
      $return[$i] = $this->getRelativePosition($run, $allRuns[$i]);
    }
    return $return;
  }

  public function getAbsolutePoints(array $positions) {
    $return = array();
    foreach ($positions as $pos) {
      $return[] = $this->getAbsolutePoint($pos);
    }
    return $return;
  }

  public function getRelativePoints(array $relativePositions) {
    $return = array();
    foreach ($relativePositions as $pos) {
      $return[] = $this->getRelativePoint($pos);
    }
    return $return;
  }



}