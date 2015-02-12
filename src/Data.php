<?php

namespace derhasi\upmkTournament;

use Symfony\Component\Yaml\Yaml;

class Data {

  /**
   * @var string
   */
  protected $dataDir;


  public function __construct($dataDir) {
    $this->dataDir = $dataDir;
  }

  protected function loadFromFile($name) {
    $filepath = $this->dataDir . '/' . $name . '.yml';
    return Yaml::parse(file_get_contents($filepath));
  }

  protected function writeToFile($name, $data) {
    $filepath = $this->dataDir . '/' . $name . '.yml';

    if (is_object($data) && method_exists($data, 'toArray')) {
      $yamlString = Yaml::dump($data->toArray());
    }
    else {
      $yamlString = Yaml::dump($data);
    }

    file_put_contents($filepath, $yamlString);
  }

  public function loadContestants() {
    $data = $this->loadFromFile('contestants');
    return $data['contestants'];
  }

  public function writeHeats($heats) {
    $this->writeToFile('heats', $heats);
  }

}