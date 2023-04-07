<?php

namespace App\Form;

use App\Controller\FrontOffice\CatalogueController;
use App\Entity\Catalogue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CatalogueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            //->add('image')
            ->add('imageFile', FileType::class, [
                'required' => false,
                'label' => 'Image',
            ])
            ->add('idCategorie')


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Catalogue::class,
        ]);
    }
}
