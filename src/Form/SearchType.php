<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Campus;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SearchType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        ->add('q', TextType::class,[
            'label'=>'Le nom de la sortie contient ',
            'required'=>false,
            'attr'=>[
                'placeholder'=>"Rechercher"]
            ])
        ->add('campus', EntityType::class,[
            'label'=>'Campus',
                'required'=>false,
                'class'=> Campus::class,
                'choice_label'=>'nom',

                'placeholder'=>"Choisir un campus"
        ])
        ->add('dateMin', DateTimeType::class, [
            'label'=>'Début',
            'required'=>false,

        ])
        ->add('dateMax',DateTimeType::class, [
            'label'=>'a',
            'required'=>false,

        ])
        ->add('organisateur', CheckboxType::class,[
            'label'=>'Sorties dont je suis l\'organisateur/trice',
            'required'=>false
        ])
        ->add('inscrit', CheckboxType::class,[
            'label'=>'Sorties auxquelles je suis inscrit/e',
            'required'=>false
        ])
        ->add('pasInscrit', CheckboxType::class,[
            'label'=>'Sorties auxquelles je ne suis pas inscrit/e',
            'required'=>false
        ])
        ->add('passees', CheckboxType::class,[
            'label'=>'Sorties passées',
            'required'=>false
        ])
        ;
}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=> SearchData::class,
            'method'=>'GET',
            'csrf_protection'=>'false'

        ]);
    }

    public function getBlockPrefix()
    {
     return '';
    }

}