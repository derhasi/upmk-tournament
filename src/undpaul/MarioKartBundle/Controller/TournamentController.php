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

            // Save the tournament to the database.
            $em = $this->getDoctrine()->getManager();
            $em->persist($tournament);
            $em->flush();

            // Provide a message.
            $this->addFlash('notice', sprintf('Tournament "%s" created!', $tournament->getName()));

            return $this->redirectToRoute('undpaul_mario_kart_tournament_index');
        }


        return $this->render('undpaulMarioKartBundle:Tournament:new.html.twig', array(
          'form' => $form->createView(),
        ));

    }
}