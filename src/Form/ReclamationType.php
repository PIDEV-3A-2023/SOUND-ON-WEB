<?php

namespace App\Form;

use App\Entity\Reclamation;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description',TextType::class, [
                'label' => 'Name',
                'constraints' => [
                    new Assert\NotBlank(),
                    
                ],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'User' => 'User',
                    'Song' => 'Song',
                    'Album' => 'Album',
                    'Artist' => 'Artist',

                ],
                'data_class' => null,

            ])
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'En Cours' => 'En Cours',
                    'Traité' => 'Traité',
                    'Suspendue' => 'Suspendue',
                    

                ],
                'data_class' => null,

            ])
            ->add('image', FileType::class, array('data_class' => null), [
                'required' => false, [
                    'mapped' => false
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
                'attr' => ['class' => 'btn btn-primary'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
