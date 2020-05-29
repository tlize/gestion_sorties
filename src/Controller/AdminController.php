<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ImportParticipantCSVType;
use App\Form\ParticipantAdminType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
     * @Route("/admin/participant", name="administrateur_index", methods={"GET"})
     * @param ParticipantRepository $participantRepository
     * @return Response
     */
    public function index(ParticipantRepository $participantRepository): Response
    {
        return $this->render('participant/index.html.twig', [
            'participants' => $participantRepository->findAll(),
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

        return $this->redirectToRoute('administrateur_index');
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
    /**
     * @Route("/import/csv", name="import_csv")
     */
    public function importUsers(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $importFrom = $this->createForm( ImportParticipantCSVType::class);
        $importFrom->handleRequest($request);
        if($importFrom->isSubmitted() && $importFrom->isValid() ){
            $fichierimport = $importFrom->get('fichier')->getData();

                if($fichierimport){
                    $originalFilename = pathinfo($fichierimport->getClientOriginalName(), PATHINFO_FILENAME);

                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII;[^A-Za-z0-9_] remove; lower()',$originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$fichierimport->guessExtension();

                    try{
                        $fichierimport->move(
                            $this->getParameter('fichier_directory'),
                            $newFilename
                        );
                    } catch (FileException $e){
                        $this->addFlash("danger", "Impossible d'enregistrer l'image");
                        return $this->redirectToRoute('administrateur_accueilAdmin');
                    }
                }
        }
        return $this->render('administrateur/addParticipantParCSV.html.twig', [
            'importFrom' =>$importFrom->createView()
        ]);
    }
//    private function getData(String $newFilename, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
//    {
//        $reader = new ods();
//        $spreadSheet = $reader->load($this->getParameter('kernel.root.dir'). '/public/uploads/ParticipantAjouts/'. $newFilename);
//            $result = $reader->fetchAssoc();
//            for ( $i = 2; $i < 100; $i++) {
//                $debut - $spreadSheet->getActiveSheet()->getcellByColumAndRow(1,$i)->getValue();
//                $user = new Participant();
//                if(is_null($debut)){
//                    $i =100;
//                }else {
//                    $user->setPseudo($spreadSheet->getActiveSheet()->getcellByColumAndRow(1, $i)->getValue());
//                        ->setPseudo
//                        ->setPrenom
//                        ->setTelephone
//                        ->setMail
//                        ->setDealer($row['mot_de_passe'])
//                        ->setDealer($row['mot_de_passe'])
//                        ->setAdministrateur($row['administrateur'])
//                        ->setActif($row['actif']);
//                    $em->persist($user);
//                }
//            }
//            $em->flush();
//            $this->addFlash('success', 'Bien ajouté avec succès');
//    }
}