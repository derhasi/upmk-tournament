<?php

date_default_timezone_set('Europe/Berlin');

use \derhasi\upmkTournament\Data;
use \derhasi\upmkTournament\Tournament;

// Load composer autoloader.
require 'vendor/autoload.php';

$data = new Data(__DIR__ . '/data');

// Load heats from tournament config.
$tournament = new Tournament($data);
$tournament->init();

// Render the heats
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig = new Twig_Environment($loader, array(
  //'cache' => __DIR__ . '/twig_cache',
));

echo $twig->render('index.twig', array('tournament' => $tournament));
