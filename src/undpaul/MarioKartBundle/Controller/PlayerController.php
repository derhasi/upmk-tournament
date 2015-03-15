<?php

namespace undpaul\MarioKartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use undpaul\MarioKartBundle\Entity\Player;
use undpaul\MarioKartBundle\Form\PlayerType;

class PlayerController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $players = $em->getRepository('undpaulMarioKartBundle:Player')->findAll();

        return $this->render('undpaulMarioKartBundle:Player:index.html.twig',
          array(
            'players' => $players,
          ));
    }

    public function newAction(Request $request)
    {
        $player = new Player();
        $form = $this->createForm(new PlayerType(), $player);
        $form->add('create_new_player', 'submit');

        $form->handleRequest($request);

        if ($form->isValid()) {

            // Save the tournament to the database.
            $em = $this->getDoctrine()->getManager();
            $em->persist($player);
            $em->flush();

            // Provide a message.
            $this->addFlash('notice',
              sprintf('Player "%s" created!', $player->getName()));

            return $this->redirectToRoute('upmk_player_index');
        }

        return $this->render('undpaulMarioKartBundle:Player:new.html.twig', array(
          'form' => $form->createView(),
        ));
    }

}
