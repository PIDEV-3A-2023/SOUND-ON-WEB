<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Utilisateur;
use App\Entity\Evenement;
         
class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('idUser', EntityType::class, [
            'class' => Utilisateur::class,
            'choice_label' => 'prenom',
            'label' => 'Prénom'
        ])
            ->add('idEvenement',EntityType::class,['class'=> Evenement::class,
            'choice_label'=>'titre',
            'label'=>'Evenement'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
