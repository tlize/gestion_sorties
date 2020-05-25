<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sortie")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/", name="sortie_index", methods={"GET"})
     */
    public function index(SortieRepository $sortieRepository): Response
    {
        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sortie_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        $organisateur = $this->getUser();
        $organisateur->addSortieOrganisee($sortie);

        if ($form->isSubmitted() && $form->isValid()) {
            $new = true;
            $this->actions($request, $sortie, $new);
            return $this->redirectToRoute('default_accueil');
        }

        return $this->render('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sortie_show", methods={"GET"})
     */
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sortie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Sortie $sortie): Response
    {
        $userco = $this->getUser();
        if($userco != $sortie->getOrganisateur()){
            $this->addFlash('warning','Vous ne pouvez pas modifier cette sortie car vous n\'etes pas l\'organisateur.');
            return $this->redirectToRoute('default_accueil');
        }
        elseif(1 != $sortie->getEtat()->getId()){ //check si l'état de la sortie vaut 1 soit "créée"
            $this->addFlash('warning','Vous ne pouvez pas modifier une sortie déjà publiée.');
            return $this->redirectToRoute('default_accueil');
        }
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);
        $new = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $this->actions($request, $sortie, $new);
            return $this->redirectToRoute('default_accueil');
        }

        return $this->render('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sortie_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Sortie $sortie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sortie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sortie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sortie_index');
    }

    /**
     * @Route("/{id}/register", name="sortie_registration", requirements={"id": "\d+"})
     */
    public function registration($id, EntityManagerInterface $em)
    {
        $userco = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $sortieRepo = $em->getRepository(Sortie::class);
        $sortie = $sortieRepo->find($id);
        $etatSortie = $sortieRepo->findAll();

        $date =  new \DateTime();

        //Conditions pour pouvoir s\inscrire a une sortie

        // ID 2 = etat ouvert
        if ($sortie->getEtat()->getId() !=2)
        {
            $this->addFlash('warning', 'L\'état de la sortie ne permet pas de vous inscrire');

        }elseif ($sortie->getNbInscriptionsMax() == $sortie->getParticipants()->count())
        {
            $this->addFlash('warning', 'Le nombre maximum de participant à été atteint');

        }elseif ($sortie->getParticipants()->contains($userco))
        {
               $this->addFlash('warning','Vous êtes déjà inscrit à la sortie');

        } elseif ($sortie->getDateLimiteInscription() < $date)
        {
            $this->addFlash('warning', 'La période d\'inscription pour cette sortie est terminée' );

        } else
        {
            $sortie->addParticipant($userco);
            $this->addFlash('success', 'Vous avez bien été inscrit à la sortie');
        }


        //Enregistrement des données (update)

        $em->flush();

        return $this->redirectToRoute('default_accueil');
    }

    /**
     * @Route("/{id}/unregister", name="sortie_unregistration", requirements={"id": "\d+"})
     */
    public function unregistration($id, EntityManagerInterface $em)
    {
        $userco = $this->getUser();
        $sortie = $this->getDoctrine()->getManager()->getRepository(Sortie::class)->find($id);
        $sortie->removeParticipant($userco);
        $em->flush();
        $this->addFlash('success', 'Vous vous êtes bien désinscrit de la sortie');
        return $this->redirectToRoute('default_accueil');
    }

    /**
     * @Route("/{id}/cancel", name="sortie_cancel", requirements={"id": "\d+"})
     */
    public function cancel($id, EntityManagerInterface $em, Request $request)
    {
        $userco = $this->getUser();
        $sortie = $this->getDoctrine()->getManager()->getRepository(Sortie::class)->find($id);
        if ($userco == $sortie->getOrganisateur()){
            $sortie->setDescription($request->get('motif'));
            $sortie->setEtat(6);
            $em->flush();
        }
        return $this->render('sortie/cancel.html.twig', ['sortie' => $sortie]);
    }

    private function actions(Request $request, Sortie $sortie, $new){
        $entityManager = $this->getDoctrine()->getManager();
        if($request->get('submit') == 'Enregistrer'){
            // Passe l'état à créée - enregistrement
            $etat = $this->getDoctrine()->getManager()->getRepository('App:Etat')->find(1);
            dump($etat);
            $sortie->setEtat($etat);
        }
        elseif ($request->get('submit') == 'Publier'){
            //Passe l'état à ouvert - publication
            $etat = $this->getDoctrine()->getManager()->getRepository('App:Etat')->find(2);
            $sortie->setEtat($etat);
        }
        elseif ($request->get('submit') == 'Supprimer la sortie'){
            $entityManager->remove($sortie);
        }
        if($new){
            $entityManager->persist($sortie);
        }
        $entityManager->flush();
    }
}
