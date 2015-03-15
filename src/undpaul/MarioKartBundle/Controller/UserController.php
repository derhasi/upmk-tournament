<?php

namespace undpaul\MarioKartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use undpaul\MarioKartBundle\Entity\User;
use undpaul\MarioKartBundle\Form\UserType;

class UserController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('undpaulMarioKartBundle:User')->findAll();

        return $this->render('undpaulMarioKartBundle:User:index.html.twig', array(
            'users' => $users,
        ));
    }

    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(new UserType(), $user);
        $form->add('create_new_user', 'submit');

        $form->handleRequest($request);

        if ($form->isValid()) {

            // Save the tournament to the database.
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // Provide a message.
            $this->addFlash('notice', sprintf('User "%s" created!', $user->getName()));

            return $this->redirectToRoute('undpaul_mario_kart_user_index');
        }

        return $this->render('undpaulMarioKartBundle:User:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
