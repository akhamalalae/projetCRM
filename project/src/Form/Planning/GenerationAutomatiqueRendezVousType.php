<?php

namespace App\Form\Planning;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Formulaire;

class GenerationAutomatiqueRendezVousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            ->add('dateExecution', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'exécution',
                'attr' => ['class' => 'form-control'],
            ])

            ->add('start', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                'attr' => ['class' => 'form-control'],
            ])

            ->add('formulaires', EntityType::class, array(
                'label' => 'Formulaires',
                'class' => Formulaire::class,
                'choice_label' => 'libelle',
                'placeholder' => 'Choisir',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                    ;
                },
                'by_reference' => false,
                'attr' => ['class' => 'js-multiple','multiple' => 'multiple'],
                'multiple'=>true,
                'required' => false,
                'expanded'=>false,
            ))

            ->add('nbrMinutes',TextType::class,[
                'label' => 'Ecart en munites entre les rendez-vous',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ]);

            $builder->add('save', SubmitType::class, [
                'label' => 'Valider',
                'attr' => ['class' => 'btn btn-primary pull-left'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            //'data_class' => 'App\Entity\Formulaire'
        ));
    }

}
