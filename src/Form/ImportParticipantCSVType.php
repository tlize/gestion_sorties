<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class ImportParticipantCSVType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fichier', FileType::class,[
//                'lable'=>'Fichier CSV',
                'mapped'=>false,
                'required'=>false,

                'constraints'=>[
                    new File([
                      'maxSize' => '1024k',
                            'mimeTypes' => [
                                'text/csv',
                                'application/vnd.oasis.opendocument.spreadsheet',
                                'application/vnd.ms-excel',
                       ],
                        'mimeTypesMessage' => 'Veuillez uploader un fichier .csv valide',

                     ])
                ]
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ImportParticipantCSVType::class,
        ]);
    }
}