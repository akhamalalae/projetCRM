<?php

namespace App\Form\TableauBord;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\TableauBordFiltresOperators;
use App\Entity\TableauBordFiltresConditions;
use App\Entity\EntitiesPropriete;
use App\Entity\Entities;
use App\Entity\Parenthese;

class TableauBordConditionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parenthese', EntityType::class, [
                'class' => Parenthese::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.libelle', 'ASC');
                },
                'choice_label' => 'libelle',
                'required' => false,
                'label' => 'Parenthèse',
                'placeholder' => 'Choisir la parenthèse',
            ])
            ->add('tableau_bord_filtre_condition', EntityType::class, [
                'class' => TableauBordFiltresConditions::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.libelle', 'ASC');
                },
                'choice_label' => 'libelle',
                'required' => false,
                'label' => 'Condition',
                'placeholder' => 'Choisir la condition',
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
                'required' => true,
                'label' => 'Entity',
                'placeholder' => 'Choisir l\'Entity',
            ])
            ->add('entities_propriete', EntityType::class, [
                'class' => EntitiesPropriete::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.status = 0')
                        ->andWhere('u.fonctionAgregation is null')
                        ->orderBy('u.libelle', 'ASC');
                },
                'choice_label' => 'getNameTypesChamps',
                'required' => true,
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
                'required' => true,
                'label' => 'Opérateur'
            ])
            ->add('valeur',TextType::class,[
                'label' => 'Valeur',
                'required' => false,
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
