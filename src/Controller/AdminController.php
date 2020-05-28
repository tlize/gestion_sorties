<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantAdminType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    public function addUser(EntityManagerInterface $entityManager, Request $request, UserPasswordEncoderInterface $encoder)
    {
        // @todo Traiter les formulaires

        $newParticipant = new Participant();
        $pForm = $this->createForm(ParticipantAdminType::class, $newParticipant);
        $pForm->handleRequest($request);

        if ($pForm->isSubmitted() && $pForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $hashed = $encoder->encodePassword($newParticipant, $newParticipant->getPassword());
            $newParticipant->setPassword($hashed);

            $entityManager->persist($newParticipant);
            $entityManager->flush();

            $this->addFlash('success', 'Le nouveau participant à bien été enregistré !');
            return $this->redirectToRoute('administrateur_addUser');
        }

        return $this->render('administrateur/addParticipant.html.twig', [
            'newparticipant' => $newParticipant,
            'pForm' => $pForm->createView(),
        ]);
        }




}