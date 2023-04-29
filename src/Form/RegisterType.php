<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
                'label' => 'Prénom',
                'constraints' => new NotBlank(),
                'constraints' => new Length([
                    'min' => 2 ,
                    'max' => 30
                ]),
                
                'attr' => [
                    'placeholder' => 'Saisir votre prenom'
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
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => new NotBlank(),
                'constraints' => new Length([
                    'min' => 2 ,
                    'max' => 30
                ]),
                
                'attr' => [
                    'placeholder' => 'Saisir votre email'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent etre identique',
                'label' => 'Mot de passe',
                'required' => true,
                'first_options' => [ 'label' => 'Mot de passe',
                'attr' => [
                    'placeholder' => 'Saisir votre mot de passe'
                ], ],
                'second_options' => [ 'label' => 'Confirmez le mot de passe',
                'attr' => [
                    'placeholder' => 'Confirmez mot de passe'
                ],]

            
            ])
            
            
            ->add('submit',SubmitType::class,[
                'label' => "S'inscrire"
                
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
