<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', null,['label' => 'Nom de la sortie :'])
            ->add('date_heure_debut', DateTimeType::class,['label' => 'Date et heure de la sortie :',
                'date_widget' => 'single_text',
                'minutes' => [0,15,30,45],])
            ->add('date_limite_inscription', DateTimeType::class,['label' => 'Date limite d\'inscription :',
                'date_widget' => 'single_text',
                'minutes' => [0,15,30,45],])
            ->add('nb_inscriptions_max', null,['label' => 'Nombre de places :'])
            ->add('duree', null,['label' => 'Durée :'])
            ->add('description', null,['label' => 'Description et infos :'])
            ->add('campus', EntityType::class,[
                'class'=>Campus::class,
                'choice_label' => 'nom',
                'label' => 'Campus :'])
        ;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this,'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this,'onPreSubmit'));
    }

    protected function addElements(FormInterface $form, Ville $ville = null)
    {
        $form->add('ville', EntityType::class, [
            'class'=>Ville::class,
            'data' => $ville,
            'label' => 'Ville :',
            'placeholder' => 'Selectionnez la ville',
            'mapped' => false,
            'required' => true,
        ]);

        $lieux = array();

        if ($ville) {
            $repoLieu = $this->em->getRepository(Lieu::class);

            $lieux = $repoLieu->createQueryBuilder("q")
                ->where("q.ville = :villeid")
                ->setParameter("villeid", $ville->getId())
                ->getQuery()
                ->getResult();
        }

        $form->add('lieu', EntityType::class, [
            'class'=>Lieu::class,
            'mapped' => true,
            'placeholder' => 'Selectionnez d\'abord la ville',
            'auto_initialize' => false,
            'choices' => $lieux,
            'required' => true,
        ]);
    }

    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();

        // Search for selected City and convert it into an Entity
        $ville = $this->em->getRepository(Ville::class)->find($data['ville']);

        $this->addElements($form, $ville);
    }

    function onPreSetData(FormEvent $event) {
        $sortie = $event->getData();
        $form = $event->getForm();

        $ville = $sortie->getLieu() ? $sortie->getLieu()->getVille() : null;

        $this->addElements($form, $ville);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sortie';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
