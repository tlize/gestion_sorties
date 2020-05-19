<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GeneralController extends AbstractController
{
    /**
     * @Route("/", name="default_accueil")
     */
    public function accueil()
        {
        return $this->render("default/accueil.html.twig", [

        ]);
        }

}
