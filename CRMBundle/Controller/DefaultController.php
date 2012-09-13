<?php

namespace CAF\CRMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('CAFCRMBundle:Default:index.html.twig', array('name' => $name));
    }
}
