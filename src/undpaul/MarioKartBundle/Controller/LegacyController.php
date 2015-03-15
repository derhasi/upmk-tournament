<?php

namespace undpaul\MarioKartBundle\Controller;

use derhasi\upmkTournament\Data;
use derhasi\upmkTournament\Tournament;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LegacyController extends Controller
{
    public function indexAction()
    {
        // @todo: find correct way to provide a data dir.
        $data_dir = $this->get('kernel')->getRootDir() . '/../data';

        $data = new Data($data_dir);

        // Load heats from tournament config.
        $tournament = new Tournament($data);
        $tournament->init();
        $tournament->buildResults();

        return $this->render('undpaulMarioKartBundle:Legacy:index.html.twig',
          array('tournament' => $tournament));
    }
}
