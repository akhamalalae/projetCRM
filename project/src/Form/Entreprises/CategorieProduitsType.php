<?php

namespace App\Form\Entreprises;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CategorieProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle',TextType::class,[
                'label' => 'Titre de l\'option',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('ordre',IntegerType::class,[
                'label' => 'Ordre',
                'required' => true,
            ])
            ->add('status',CheckboxType::class,[
                'label' => 'Inactive',
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Valider',
                'attr' => ['class' => 'btn btn-primary pull-left'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => 'App\Entity\CategorieProduits'
        ]);
    }

}
