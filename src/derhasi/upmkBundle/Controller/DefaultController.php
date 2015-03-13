<?php

namespace derhasi\upmkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('upmkBundle:Default:index.html.twig', array('name' => $name));
    }
}
