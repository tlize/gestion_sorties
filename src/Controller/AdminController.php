<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantAdminType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="administrateur_accueilAdmin")
     */
    public function accueilAdmin()
    {
            return $this->render("administrateur/adminAccueil.html.twig");
    }

    /**
     * @Route("/add", name="administrateur_addUser")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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

    /**
     *
     * @Route("/{id}/change_actif", name="administrateur_change_actif", requirements={"id": "\d+"})
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param Participant $participant
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeActif(EntityManagerInterface $entityManager, Request $request, Participant $participant)
    {
        if($participant->getActif() == 0){
            $participant->setActif(1);
        }else{
            $participant->setActif(0);
        }
        $entityManager->flush();

        $this->addFlash('success', 'L\'etat de '.$participant->getPseudo().' a été changé !');

        return $this->redirectToRoute('participant_index');
    }





}