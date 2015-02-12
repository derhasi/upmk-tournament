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
      $yamlString = Yaml::dump($data, 4);
    }

    file_put_contents($filepath, $yamlString);
  }

  public function loadConfig() {
    $data = $this->loadFromFile('config');
    return $data;
  }

  public function writeHeatRaces($heats) {
    $this->writeToFile('heat-races', $heats);
  }

  public function loadHeatRaces() {
    $data = $this->loadFromFile('heat-races');
    return $data;
  }

}