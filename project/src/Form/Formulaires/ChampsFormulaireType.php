<?php

namespace App\Form\Formulaires;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Typeschamps;
use App\Entity\Referentiels;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ChampsFormulaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle',TextType::class,[
                'label' => 'Titre du champ',
                'required' => true,
            ])
            ->add('description',TextareaType::class,[
                'label' => 'Description du champ',
                'required' => false,
            ])

            ->add('type', EntityType::class, [
                'class' => Typeschamps::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.libelle', 'ASC');
                },
                'choice_label' => 'libelle',
                'placeholder' => 'Choisir le type du champ',
                'label' => 'Type du champ'
            ])
            ->add('referentiels', EntityType::class, [
                'class' => Referentiels::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.status = 0')
                        ->orderBy('u.libelle', 'ASC');
                },
                //'attr' => ['style' => 'display:none'],
                'choice_label' => 'libelle',
                'required' => false,
                'placeholder' => 'Choisir le Référentiels',
                'label' => ' '
            ])
/*
            ->add('referentiel_entity', EntityType::class, [
                'class' => Entity::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.referentielEntity = 0')
                        ->orderBy('u.libelle', 'ASC');
                },
                //'attr' => ['style' => 'display:none'],
                'choice_label' => 'libelle',
                'required' => false,
                'placeholder' => 'Choisir le Référentiels',
                'label' => ' '
            ])
*/
            // this is the embeded form, the most important things are highlighted at the bottom
            ->add('options', CollectionType::class, [
                'entry_type' => OptionsType::class,
                'entry_options' => [
                    'label' => false
                ],
                'label' => false,
                'by_reference' => false,
                'required' => false,
                // this allows the creation of new forms and the prototype too
                'allow_add' => true,
                // self explanatory, this one allows the form to be removed
                'allow_delete' => true
            ])

            ->add('ordre',IntegerType::class,[
                'label' => 'Ordre du champ dans le formulaire',
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
            'data_class' => 'App\Entity\ChampsFormulaire'
        ]);
    }
}
