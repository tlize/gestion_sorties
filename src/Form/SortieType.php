<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', null,['label' => 'Nom de la sortie :'])
            ->add('date_heure_debut', null,['label' => 'Date et heure de la sortie :'])
            ->add('date_limite_inscription', null,['label' => 'Date limite d\'inscritpion :'])
            ->add('nb_inscriptions_max', null,['label' => 'Nombre de places :'])
            ->add('duree', null,['label' => 'Durée :'])
            ->add('description', null,['label' => 'Description et infos :'])
            ->add('campus')
            ->add('lieu', null,['label' => 'Lieu :'])
            ->add('ville',VilleType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
