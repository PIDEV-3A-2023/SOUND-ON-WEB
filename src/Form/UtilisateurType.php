<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => new NotBlank(),
                'constraints' => new Length([
                    'min' => 2 ,
                    'max' => 30
                ]),
                
                'attr' => [
                    'placeholder' => 'Saisir email'
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices'  => [
                  'User' => 'ROLE_USER',
                  'Admin' => 'ROLE_ADMIN',
                ],
            ])
            ->add('password')
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'constraints' => new NotBlank(),
                'constraints' => new Length([
                    'min' => 2 ,
                    'max' => 30
                ]),
               
                'attr' => [
                    'placeholder' => 'Saisir votre nom'
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Nom',
                'constraints' => new NotBlank(),
                'constraints' => new Length([
                    'min' => 2 ,
                    'max' => 30
                ]),
               
                'attr' => [
                    'placeholder' => 'Saisir  prenom'
                ]
            ])
            ->add('login', TextType::class, [
                'label' => 'Login',
                'constraints' => new NotBlank(),
                'constraints' => new Length([
                    'min' => 2 ,
                    'max' => 30
                ]),
                
                'attr' => [
                    'placeholder' => 'Saisir votre login'
                ]
            ])
        ;

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                     // transform the array to a string
                     return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                     // transform the string back to an array
                     return [$rolesString];
                }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
