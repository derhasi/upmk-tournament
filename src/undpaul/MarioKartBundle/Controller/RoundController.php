<?php

namespace undpaul\MarioKartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use undpaul\MarioKartBundle\Entity\Round;

class RoundController extends Controller
{
    public function generateAction($tournament_id, Request $request)
    {
        /**
         * @var $tournament \undpaul\MarioKartBundle\Entity\Tournament
         */
        $em = $this->getDoctrine()->getManager();
        $tournament = $em->getRepository('undpaulMarioKartBundle:Tournament')
          ->find($tournament_id);

        $form = $this->createFormBuilder()
          ->add('number_of_races', 'integer', array(
            'required' => true,
            'data' => 3,
            'constraints' => array(
              new NotBlank(),
              new GreaterThan(array('value' => 0)),
            ),
          ))
          ->add('generate', 'submit')
          ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {

            $data = $form->getData();

            $round = new Round();
            $round->setTournament($tournament)
                ->setDelta(count($tournament->getRounds()));

            $round->generateGames($data['number_of_races']);

            $em->persist($round);

            $tournament->addRound($round);
            $em->flush();

            return $this->redirectToRoute('undpaul_mario_kart_tournament_view', array(
                'tournament_id' => $tournament->getId(),
            ));
        }


        return $this->render('undpaulMarioKartBundle:Round:generate.html.twig', array(
          'tournament' => $tournament,
          'form' => $form->createView(),
        ));
    }
}
