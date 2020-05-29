<?php

namespace App\Command;

use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CheckEtatCommand extends Command
{
    protected static $defaultName = 'app:check-etat';

    protected $em;
    protected $sortieRepository;
    protected $etatRepository;

    public function __construct(EntityManagerInterface $em, SortieRepository $sortieRepository, EtatRepository $etatRepository)
    {
        $this->em = $em;
        $this->sortieRepository = $sortieRepository;
        $this->etatRepository = $etatRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Permet de changer les états des sorties en BDD');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dateActuelle = new \DateTime('now');
        $etatCree = $this->etatRepository->find(1);
        $etatOuvert = $this->etatRepository->find(2);
        $etatCloture = $this->etatRepository->find(3);
        $etatEnCours = $this->etatRepository->find(4);
        $etatPasse = $this->etatRepository->find(5);
        $etatAnnule = $this->etatRepository->find(6);
        $sorties = $this->sortieRepository->findAll();
        $compteurLigne = 0;

        foreach ($sorties as $sortie){
            $dateLimiteInscription = $sortie->getDateLimiteInscription();
            $dateHeureDebut = $sortie->getDateHeureDebut();
            $duree = $sortie->getDuree();
            $etat = $sortie->getEtat();
            $libelle = $sortie->getEtat()->getLibelle();
            $etatid = $sortie->getEtat()->getId();
            $id = $sortie->getId();
            //Switch ouvert => fermé ou ouvert => en cours
            if($dateLimiteInscription<$dateActuelle && $etat === $etatOuvert){
                //si la date de limite d'inscription est la même que l'heure de début, passage à en cours
                if($dateLimiteInscription == $dateHeureDebut){
                    $sortie->setEtat($etatEnCours);
                    $output->writeln('La sortie numéro '.$id.' a été modifiée, passage de l\'état '.$libelle.' ('
                        .$etatid.') à l\'état en cours (4).');
                }
                else{
                    $sortie->setEtat($etatCloture);
                    $output->writeln('La sortie numéro '.$id.' a été modifiée, passage de l\'état '.$libelle.' ('
                        .$etatid.') à l\'état cloturé (3).');
                }
                $compteurLigne++;
            }
            //Switch cloture => en cours
            if($dateHeureDebut < $dateActuelle && $etat === $etatCloture) {
                $sortie->setEtat($etatEnCours);
                $compteurLigne++;
                $output->writeln('La sortie numéro '.$id.' a été modifiée, passage de l\'état '.$libelle.' ('
                    .$etatid.') à l\'état en cours (4).');
            }
            //Switch en cours ou annulée => passé
            if($etat === $etatEnCours || $etat === $etatAnnule) {
                $dureeAjout = new \DateInterval('PT'.$duree.'M');
                $dateFin = date_add($dateHeureDebut,$dureeAjout);
                $output->writeln($dateFin->format('d-m-Y H:i:s'));
                if($dateFin < $dateActuelle){
                    $sortie->setEtat($etatPasse);
                    $compteurLigne++;
                    $output->writeln('La sortie numéro '.$id.' a été modifiée, passage de l\'état '.$libelle.' ('
                        .$etatid.') à l\'état passée (5).');
                }
            }
        }
        $this->em->flush();
        $output->writeln(''.$compteurLigne.' lignes ont été modifiées en base de donnée.');
    }
}
