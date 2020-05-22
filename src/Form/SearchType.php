<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Campus;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            'label'=>'campus',
                'required'=>false,
                'class'=> Campus::class,
                'choice_label'=>'nom'
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