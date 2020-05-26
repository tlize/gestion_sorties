<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantAdminType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="administrateur_accueilAdmin")
     */
    public function accueilAdmin()
    {


            return $this->render("administrateur/adminAccueil.html.twig");

    }

    /**
     * @Route("/admin/add", name="administrateur_addUser")
     */
    public function addUser(EntityManagerInterface $entityManager, Request $request)
    {
        // @todo Traiter les formulaires

        $newParticipant = new Participant();
        $pForm = $this->createForm(ParticipantAdminType::class, $newParticipant);
        $pForm->handleRequest($request);

        if ($pForm->isSubmitted() && $pForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newParticipant);
            $entityManager->flush();

            return $this->redirectToRoute('participant_index');
        }

        return $this->render('administrateur/addParticipant.html.twig', [
            'newparticipant' => $newParticipant,
            'pForm' => $pForm->createView(),
        ]);
        }


}