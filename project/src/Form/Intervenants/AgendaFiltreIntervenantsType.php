<?php

namespace App\Form\Intervenants;

//use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\User;
use App\Entity\Entreprise;
use App\Entity\Produit;
use App\Entity\CategorieProduits;
use App\Entity\ChampsFormulaire;
use App\Entity\Typeschamps;
use App\Entity\Departments;
use App\Entity\MenuSousCategorie;
use App\Entity\Formulaire;
use App\Entity\PointVente;

class AgendaFiltreIntervenantsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('intervenants', EntityType::class, array(
                'label' => 'Filtres Intervenants',
                'class' => User::class,
                'choice_label' => 'lastname',
                'placeholder' => 'Choisir les Intervenants',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                    ;
                },
                'by_reference' => false,
                'attr' => ['class' => 'form-control me-2 js-multiple','multiple' => 'multiple','type' => 'search'],
                'multiple'=>true,
                'expanded'=>false,
            ))

            ->add('entreprises', EntityType::class, array(
                'label' => 'Filtres Entreprises',
                'class' => Entreprise::class,
                'choice_label' => 'formeJuridique',
                'placeholder' => 'Choisir les Entreprises',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                    ;
                },
                'by_reference' => false,
                'required' => false,
                'attr' => ['class' => 'js-multiple','multiple' => 'multiple'],
                'multiple'=>true,
                'expanded'=>false,
            ))

            ->add('formulaire', EntityType::class, [
                'class' => Formulaire::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.libelle', 'ASC');
                },
                'attr' => ['class' => 'js-multiple','multiple' => 'multiple'],
                'choice_label' => 'libelle',
                'placeholder' => 'Choisir',
                'label' => 'Filtres Formulaires',
                'by_reference' => false,
                'required' => false,
                'multiple'=>true,
                'expanded'=>false,
            ])

            ->add('pointeVente', EntityType::class, [
                'class' => PointVente::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.libelle', 'ASC');
                },
                'attr' => ['class' => 'js-multiple','multiple' => 'multiple'],
                'choice_label' => 'libelle',
                'placeholder' => 'Choisir',
                'label' => 'Filtres Point de ventes',
                'by_reference' => false,
                'required' => false,
                'multiple'=>true,
                'expanded'=>false,
            ]);

            $builder->add('save', SubmitType::class, [
                'label' => 'Valider',
                'attr' => ['class' => 'btn btn-dark pull-left'],
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
