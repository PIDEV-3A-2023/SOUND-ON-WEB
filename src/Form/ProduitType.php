<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Produit;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prix' , MoneyType::class)
            ->add('image', FileType::class, [
                // unmapped means that this field is not associated to any entity property
                'data_class' => null,
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image',
                    ])
                ]])
            ->add('libelle',TextType::class ,[
                'constraints' => new Length([
                    'min'=>3,
                    'max'=>20,
                ])
            ])
            ->add('type' , ChoiceType::class, [
                'choices' => [
                    'Vetements' => 'vetements',
                    'Affiche' => 'Affiche',
                    'Albums' =>'Albums',
                    'Other' =>'Other',
                ],
            ])
            ->add('quantite', NumberType::class, [
                'constraints' => new Range([
                    'min' => 1,
                    'max' => 10
                ]),
            ])
            
            ->add('Description', TextareaType::class)
        ;
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }



   
}