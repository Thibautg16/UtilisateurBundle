<?php

namespace Thibautg16\UtilisateurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Thibautg16UtilisateurBundle:Default:index.html.twig', array('name' => $name));
    }
}
