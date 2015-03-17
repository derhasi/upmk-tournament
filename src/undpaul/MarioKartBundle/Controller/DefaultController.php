<?php

namespace undpaul\MarioKartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('undpaulMarioKartBundle:Default:index.html.twig');
    }
}
