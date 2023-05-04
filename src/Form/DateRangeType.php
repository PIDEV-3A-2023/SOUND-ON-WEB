<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DateRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('etat', ChoiceType::class, [
                    'choices' => [
                        ' '=> ' ',
                        'En Cours' => 'En Cours',
                        'Traité' => 'Traité',
                        'Fermé' => 'Fermé'
                    ],
                    'data_class' => null,
    
            ])
            ->add('type', ChoiceType::class, [
                    'choices' => [
                        ' '=> ' ',
                        'User' => 'User',
                        'Song' => 'Song',
                        'Album' => 'Album',
                        'Artist' => 'Artist',
                        
                       
    
                    ],
                    'data_class' => null,
    
                
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure form options if needed
        ]);
    }


    public function validateEndDate($value, ExecutionContextInterface $context)
    {


        // Ensure the end date is greater than the start date
        $form = $context->getRoot();
        $startDate = $form['start']->getData();
        if ($startDate && $value < $startDate) {
            $context->buildViolation('The end date must be greater than the start date.')
                ->addViolation();
        }
    }
}