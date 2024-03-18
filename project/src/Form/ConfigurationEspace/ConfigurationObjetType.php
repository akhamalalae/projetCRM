<?php

namespace App\Form\ConfigurationEspace;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ConfigurationObjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle',TextType::class,[
                'label' => 'Nom éspace',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])

            ->add('save', SubmitType::class, [
                'label' => 'Valider',
                'attr' => ['class' => 'btn btn-primary'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\ConfigurationObjet'
        ));
    }

}
