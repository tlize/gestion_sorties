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
     * @param SortieRepository $sortieRepository
     * @param Request $request
     * @return Response
     */
    public function accueil(SortieRepository $sortieRepository,Request $request): Response
    {
        $userco =$this->getUser();
        $userid =$userco ->getId();
        $data = new SearchData();
        $formTri = $this->createForm(SearchType::class, $data);
        $formTri->handleRequest($request);

        $generals = $sortieRepository->findPageAcceuil($data,$userid);

        return $this->render("default/accueil.html.twig", [
            'sorties' => $generals,
            'formTri'=>$formTri->createView()
        ]);
    }




}
