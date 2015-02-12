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

// Load heats from tournament config.
$tournament = new upmkTournament\Tournament($names);
$heats = $tournament->getHeats();

// Render the heats
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig = new Twig_Environment($loader, array(
  //'cache' => __DIR__ . '/twig_cache',
));

echo $twig->render('index.twig', array('tournament' => $tournament));
