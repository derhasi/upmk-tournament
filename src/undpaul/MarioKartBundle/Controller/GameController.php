<?php

namespace undpaul\MarioKartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use undpaul\MarioKartBundle\Entity\Game;
use undpaul\MarioKartBundle\Entity\RankingGame;
use undpaul\MarioKartBundle\Form\GameType;

class GameController extends Controller
{
    public function viewAction($game_id)
    {
        /**
         * @var \undpaul\MarioKartBundle\Entity\Game $game
         */
        $em = $this->getDoctrine()->getManager();
        $game = $em->getRepository('undpaulMarioKartBundle:Game')
          ->find($game_id);

        return $this->render('undpaulMarioKartBundle:Game:view.html.twig', array(
          'game' => $game,
        ));
    }

    public function viewFullAction(Game $game, $titleTag = 'h1')
    {

        // Provide ranking for the given game.
        $ranking = new RankingGame($game);

        return $this->render('undpaulMarioKartBundle:Game:view.full.html.twig', array(
          'game' => $game,
          'titleTag' => $titleTag,
          'ranking' => $ranking->calculate(),
        ));
    }

    public function editAction($game_id, Request $request)
    {
        /**
         * @var Game $game
         */
        $em = $this->getDoctrine()->getManager();
        $game = $em->getRepository('undpaulMarioKartBundle:Game')
          ->find($game_id);

        $form = $this->createForm(new GameType(), $game)
          ->add('save', 'submit');

        $form->handleRequest($request);

        if ($form->isValid()) {

            // Save the tournament to the database.
            $em = $this->getDoctrine()->getManager();
            $em->persist($game);
            $em->flush();

            // Provide a message.
            $this->addFlash('notice',
              sprintf('Game "%s" updated!', $game->getFullName()));

            return $this->redirectToRoute('upmk_round_view', array('round_id' => $game->getRound()->getId()));
        }

        return $this->render('undpaulMarioKartBundle:Game:edit.html.twig', array(
          'game' => $game,
          'form' => $form->createView(),
        ));
    }

}
