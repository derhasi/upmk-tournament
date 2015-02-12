<?php
/**
 * Created by PhpStorm.
 * User: derhasi
 * Date: 12.02.15
 * Time: 09:46
 */

namespace derhasi\upmkTournament;


class ContestantCollection extends CollectionBase {

  public function __construct($names = array()) {
    $this->addMultipleByName($names);
  }

  /**
   * @param string $name
   */
  public function addByName($name) {
    $this->add(new Contestant($name));
  }

  /**
   * @param string[] $names
   */
  public function addMultipleByName($names) {
    foreach ($names as $name) {
      $this->addByName($name);
    }
  }

}