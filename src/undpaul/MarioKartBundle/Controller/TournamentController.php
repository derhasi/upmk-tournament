<?php

namespace undpaul\MarioKartBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use undpaul\MarioKartBundle\Entity\Participation;
use undpaul\MarioKartBundle\Entity\RankingTournament;
use undpaul\MarioKartBundle\Entity\Tournament;
use undpaul\MarioKartBundle\Entity\Player;
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

            return $this->redirectToRoute('upmk_tournament_index');
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

        $ranking = new RankingTournament($tournament);

        return $this->render('undpaulMarioKartBundle:Tournament:view.html.twig',
          array(
            'tournament' => $tournament,
            'ranking' => $ranking,
          ));

    }

    public function addPlayerAction($tournament_id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        /**
         * @var $tournament \undpaul\MarioKartBundle\Entity\Tournament
         */
        $tournament = $em->getRepository('undpaulMarioKartBundle:Tournament')
          ->find($tournament_id);

        if ($tournament->isStarted()) {
            return $this->render('undpaulMarioKartBundle:Tournament:addPlayer.html.twig',
              array(
                'tournament' => $tournament,
              ));
        }

        $players_ids = $tournament->getPlayers()->map(function (
          Player $player
        ) {
            return $player->getId();
        })->toArray();

        $form = $this->createFormBuilder(array())
          ->add('select_existing_player', 'entity', array(
            'class' => 'undpaulMarioKartBundle:Player',
            'property' => 'name',
            'required' => false,
            'query_builder' => function (EntityRepository $er) use (
              $players_ids
            ) {
                $queryBuilder = $er->createQueryBuilder('u');
                // Limit players to those who are not participating already.
                if (count($players_ids)) {
                    $queryBuilder->where(
                      $queryBuilder->expr()->notIn('u.id', $players_ids)
                    );
                }

                return $queryBuilder;
            },
          ))
          ->add('create_new_player', 'text', array('required' => false))
          ->add('add', 'submit')
          ->add('add_another', 'submit')
          ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            // Add player if one was selected.
            if ($data['select_existing_player']) {
                // Create new participation for selected player.
                $participation = new Participation();
                $participation->setPlayer($data['select_existing_player']);
                $participation->setTournament($tournament);
                $em->persist($participation);
                $tournament->addParticipation($participation);

                $this->addFlash('notice',
                  sprintf('Added "%s" to "%s"!',
                    $data['select_existing_player']->getName(),
                    $tournament->getName()));
            }
            // And/or create a new player and add it to the list.
            if ($data['create_new_player']) {
                $u = new Player();
                $u->setName($data['create_new_player']);
                $em->persist($u);

                $participation = new Participation();
                $participation->setPlayer($u);
                $participation->setTournament($tournament);
                $em->persist($participation);

                $tournament->addParticipation($participation);

                $this->addFlash('notice',
                  sprintf('Created "%s" and added to "%s"!',
                    $u->getName(),
                    $tournament->getName()));
            }

            $em->flush();

            if ($form->get('add')->isClicked()) {
                return $this->redirectToRoute('upmk_tournament_view',
                  array(
                    'tournament_id' => $tournament->getId(),
                  ));
            } else {
                return $this->redirectToRoute('upmk_tournament_add_player',
                  array(
                    'tournament_id' => $tournament->getId(),
                  ));
            }

        }

        return $this->render('undpaulMarioKartBundle:Tournament:addPlayer.html.twig',
          array(
            'tournament' => $tournament,
            'form' => $form->createView(),
          ));
    }
}