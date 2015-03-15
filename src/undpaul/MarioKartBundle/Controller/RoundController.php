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

            return $this->redirectToRoute('upmk_tournament_view', array(
                'tournament_id' => $tournament->getId(),
            ));
        }


        return $this->render('undpaulMarioKartBundle:Round:generate.html.twig', array(
          'tournament' => $tournament,
          'form' => $form->createView(),
        ));
    }

    public function removeAction($round_id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $round = $em->getRepository('undpaulMarioKartBundle:Round')
          ->find($round_id);

        if (!$round) {
            return $this->createNotFoundException();
        }

        $form = $this->createFormBuilder()
          ->add('remove', 'submit')
          ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {

            if ($form->get('remove')->isClicked()) {
                $em->remove($round);
                $em->flush();

                $this->addFlash('notice', sprintf('Round %d removed from %s',
                  $round->getDelta() + 1,
                  $round->getTournament()->getName()
                ));

                return $this->redirectToRoute('upmk_tournament_view', [
                    'tournament_id' => $round->getTournament()->getId(),
                ]);
            }
        }

        return $this->render('undpaulMarioKartBundle:Round:remove.html.twig',
          array(
          'round' => $round,
          'form' => $form->createView(),
        ));
    }
}
