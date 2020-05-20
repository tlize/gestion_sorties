<?php

namespace App\Controller;

use App\Entity\PropertySearch;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GeneralController extends AbstractController
{
    /**
     * @Route("/", name="default_accueil")
     */
    public function accueil(SortieRepository $sortieRepository,Request $request): Response
    {


        return $this->render("default/accueil.html.twig", [
            'sorties' => $sortieRepository->findPageAcceuil(),
        ]);
    }





}
