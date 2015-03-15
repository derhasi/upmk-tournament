<?php

namespace undpaul\MarioKartBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use undpaul\MarioKartBundle\Entity\Tournament;
use undpaul\MarioKartBundle\Entity\User;
use undpaul\MarioKartBundle\Form\TournamentType;

class TournamentController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tournaments = $em->getRepository('undpaulMarioKartBundle:Tournament')
          ->findAll();

        return $this->render('undpaulMarioKartBundle:Tournament:index.html.twig',
          array(
            'tournaments' => $tournaments,
          ));
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
            $this->addFlash('notice',
              sprintf('Tournament "%s" created!', $tournament->getName()));

            return $this->redirectToRoute('undpaul_mario_kart_tournament_index');
        }

        return $this->render('undpaulMarioKartBundle:Tournament:new.html.twig',
          array(
            'form' => $form->createView(),
          ));

    }

    public function viewAction($tournament_id)
    {
        $em = $this->getDoctrine()->getManager();
        $tournament = $em->getRepository('undpaulMarioKartBundle:Tournament')
          ->find($tournament_id);

        return $this->render('undpaulMarioKartBundle:Tournament:view.html.twig',
          array(
            'tournament' => $tournament,
          ));

    }

    public function addContestantAction($tournament_id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        /**
         * @var $tournament \undpaul\MarioKartBundle\Entity\Tournament
         */
        $tournament = $em->getRepository('undpaulMarioKartBundle:Tournament')
          ->find($tournament_id);

        if ($tournament->isStarted()) {
            return $this->render('undpaulMarioKartBundle:Tournament:addContestant.html.twig',
              array(
                'tournament' => $tournament,
              ));
        }

        $contestants_ids = $tournament->getContestants()->map(function (
          User $user
        ) {
            return $user->getId();
        })->toArray();

        $form = $this->createFormBuilder(array())
          ->add('select_existing_user', 'entity', array(
            'class' => 'undpaulMarioKartBundle:User',
            'property' => 'name',
            'required' => false,
            'query_builder' => function (EntityRepository $er) use (
              $contestants_ids
            ) {
                $queryBuilder = $er->createQueryBuilder('u');
                // Limit users to those who are not participating already.
                if (count($contestants_ids)) {
                    $queryBuilder->where(
                      $queryBuilder->expr()->notIn('u.id', $contestants_ids)
                    );
                }

                return $queryBuilder;
            },
          ))
          ->add('create_new_user', 'text')
          ->add('add', 'submit')
          ->add('add_another', 'submit')
          ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            // Add contestant if one was selected.
            if ($data['select_existing_user']) {
                $tournament->addContestant($data['select_existing_user']);

                $this->addFlash('notice',
                  sprintf('Added "%s" to "%s"!',
                    $data['select_existing_user']->getName(),
                    $tournament->getName()));
            }
            // And/or create a new user and add it to the list.
            if ($data['create_new_user']) {
                $u = new User();
                $u->setName($data['create_new_user']);
                $em->persist($u);
                $tournament->addContestant($u);

                $this->addFlash('notice',
                  sprintf('Created "%s" and added to "%s"!',
                    $u->getName(),
                    $tournament->getName()));
            }

            $em->flush();

            if ($form->get('add')->isClicked()) {
                return $this->redirectToRoute('undpaul_mario_kart_tournament_view',
                  array(
                    'tournament_id' => $tournament->getId(),
                  ));
            } else {
                return $this->redirectToRoute('undpaul_mario_kart_tournament_add_contestant',
                  array(
                    'tournament_id' => $tournament->getId(),
                  ));
            }

        }

        return $this->render('undpaulMarioKartBundle:Tournament:addContestant.html.twig',
          array(
            'tournament' => $tournament,
            'form' => $form->createView(),
          ));
    }
}