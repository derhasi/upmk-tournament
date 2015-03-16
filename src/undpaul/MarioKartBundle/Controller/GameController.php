<?php

namespace undpaul\MarioKartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use undpaul\MarioKartBundle\Entity\Game;

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
        return $this->render('undpaulMarioKartBundle:Game:view.full.html.twig', array(
          'game' => $game,
          'titleTag' => $titleTag,
        ));
    }

    public function editAction($game_id)
    {
        return $this->render('undpaulMarioKartBundle:Game:edit.html.twig', array(
                // ...
            ));
    }

}
