<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Categorie;
use App\Entity\Musique;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MusiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $object = $options['data'] ?? null;
        $isEdit = $object && $object->getId();

        $builder
            ->add('nom')
            ->add('chemin', FileType::class, [
                'label' => 'Fichier audio',
                'mapped' => false,
                'required' => !$isEdit,
                'constraints' => [
                    new File([
                        'maxSize' => '10Mi',
                        'mimeTypes' => [
                            'audio/x-wav',
                            'audio/mpeg'
                        ],
                        'mimeTypesMessage' => 'Veuillez choisir une fichier audio'
                    ])
                ]
            ])
            ->add('dateCreation')
            ->add('longueur')
            ->add('idUser', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'email',
            ])
            ->add('idCategorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
            ])
            ->add('idAlbum', EntityType::class, [
                'class' => Album::class,
                'choice_label' => 'nom',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Musique::class,
        ]);
    }
}
