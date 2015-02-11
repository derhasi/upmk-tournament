<?php

use derhasi\upmkTournament;

date_default_timezone_set('Europe/Berlin');

// Load composer autoloader.
require 'vendor/autoload.php';

$names = [
  'A',
  'B',
  'C',
  'D',
  'E',
  'F',
  'G',
  'H',
  'I',
  'J',
];


$tournament = new upmkTournament\Tournament($names);

$tournament->buildHeats();
