<?php

namespace App\Form\Referentiels;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ReferentielsOptionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle',TextType::class,[
                'label' => 'Titre de l\'option',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('description',TextareaType::class,[
                'label' => 'Description de l\'option',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('status',CheckboxType::class,[
                'label' => 'Inactive',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => 'App\Entity\ReferentielsOptions'
        ]);
    }

}
