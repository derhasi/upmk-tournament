<?php

namespace undpaul\MarioKartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use undpaul\MarioKartBundle\Entity\Tournament;
use undpaul\MarioKartBundle\Form\TournamentType;

class TournamentController extends Controller
{
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('undpaul_mario_kart_legacy'));
    }


    public function newAction(Request $request)
    {
        $tournament = new Tournament();
        $form = $this->createForm(new TournamentType(), $tournament);

        $form->handleRequest($request);

        if ($form->isValid()) {

            // @todo: write
            $request->getSession()->getFlashBag()->set('notice', 'Message sent!');

            return $this->redirectToRoute('undpaul_mario_kart_tournament_index');
        }


        return $this->render('undpaulMarioKartBundle:Tournament:new.html.twig', array(
          'form' => $form->createView(),
        ));

    }
}