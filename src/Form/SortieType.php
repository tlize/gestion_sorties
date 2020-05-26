<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', null,['label' => 'Nom de la sortie :'])
            ->add('date_heure_debut', DateTimeType::class,['label' => 'Date et heure de la sortie :',
                'date_widget' => 'single_text',
                'minutes' => [0,15,30,45],
                'data' => new \DateTime('+1 hours')])
            ->add('date_limite_inscription', DateTimeType::class,['label' => 'Date limite d\'inscription :',
                'date_widget' => 'single_text',
                'minutes' => [0,15,30,45],
                'data' => new \DateTime('+1 hours')])
            ->add('nb_inscriptions_max', null,['label' => 'Nombre de places :'])
            ->add('duree', null,['label' => 'Durée :'])
            ->add('description', null,['label' => 'Description et infos :'])
            ->add('campus', EntityType::class,[
                'class'=>Campus::class,
                'choice_label' => 'nom',
                'label' => 'Campus :'])
            ->add('Ville', EntityType::class,[
                'class'=>Ville::class,
                'choice_label' => 'nom',
                'label' => 'Ville :'])
            ->add('lieu', EntityType::class,[
                'class'=>Lieu::class,
                'choice_label' => 'nom',
                'label' => 'Lieu :'])
            //->add('ville',VilleType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
