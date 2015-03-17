<?php

namespace undpaul\MarioKartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use undpaul\MarioKartBundle\Entity\Participation;

class ParticipationController extends Controller
{

    public function viewAction($pid)
    {
        $em = $this->getDoctrine()->getManager();
        $participation = $em->getRepository('undpaulMarioKartBundle:Participation')
          ->find($pid);

        // Show duells
        $duellranking = $participation->getDuellRankings();


        return $this->render('undpaulMarioKartBundle:Participation:view.html.twig', array(
          'participation' => $participation,
          'duellranking' => $duellranking,
        ));
    }


    public function removeAction($pid, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Participation $participation */
        $participation = $em->getRepository('undpaulMarioKartBundle:Participation')
          ->find($pid);

        if (!$participation) {
            return $this->createNotFoundException();
        }

        $form = $this->createFormBuilder()
          ->add('remove', 'submit')
          ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {

            if ($form->get('remove')->isClicked()) {
                $em->remove($participation);
                $em->flush();

                $this->addFlash('notice', sprintf('%s removed from %s',
                  $participation->getPlayer()->getName(),
                  $participation->getTournament()->getName()
                ));

                return $this->redirectToRoute('upmk_tournament_view', [
                  'tournament_id' => $participation->getTournament()->getId(),
                ]);
            }
        }

        return $this->render('undpaulMarioKartBundle:Participation:remove.html.twig', array(
          'participation' => $participation,
          'form' => $form->createView(),
        ));
    }

}
