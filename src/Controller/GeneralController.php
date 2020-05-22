<?php

namespace App\Controller;

use App\Data\SearchData;


use App\Form\SearchType;
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
        $data = new SearchData();
        $formTri = $this->createForm(SearchType::class, $data);
        $formTri->handleRequest($request);

        $generals = $sortieRepository->findPageAcceuil($data);

        return $this->render("default/accueil.html.twig", [
            'sorties' => $generals,
            'formTri'=>$formTri->createView()
        ]);
    }




}
