<?php

namespace App\Form\TableauBord;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\TableauBordFiltresOperators;
use App\Entity\TableauBordFiltresConditions;
use App\Entity\EntitiesPropriete;
use App\Entity\Entities;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class TableauBordConditionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tableau_bord_filtre_condition', EntityType::class, [
                'class' => TableauBordFiltresConditions::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.libelle', 'ASC');
                },
                'choice_label' => 'libelle',
                'required' => false,
                'placeholder' => 'Choisir la condition',
                'label' => false
            ])
            ->add('entitie', EntityType::class, [
                'class' => Entities::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.status = 0')
                        ->orderBy('u.libelle', 'ASC');
                },
                //'attr' => ['style' => 'display:none'],
                'choice_label' => 'libelle',
                'required' => false,
                'label' => 'Entity',
                'placeholder' => 'Choisir l\'Entity',
            ])
            ->add('entities_propriete', EntityType::class, [
                'class' => EntitiesPropriete::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.status = 0')
                        ->orderBy('u.libelle', 'ASC');
                },
                'choice_label' => 'getNameTypesChamps',
                'required' => false,
                'placeholder' => 'Choisir le champ',
                'label' => "Champ"
            ])
            ->add('tableau_bord_filtre_operator', EntityType::class, [
                'class' => TableauBordFiltresOperators::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.libelle', 'ASC');
                },
                'choice_label' => 'libelle',
                'placeholder' => 'Choisir l\'opérateur',
                'label' => 'Opérateur'
            ])
            ->add('valeur',TextType::class,[
                'label' => 'Valeur',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => 'App\Entity\RequeteTableauBordFiltres'
        ]);
    }
}
