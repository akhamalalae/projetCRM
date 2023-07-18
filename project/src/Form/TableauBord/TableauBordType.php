<?php

namespace App\Form\TableauBord;

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
use App\Entity\Formulaire;
use App\Entity\Produit;
use App\Entity\CategorieProduits;
use App\Entity\ChampsFormulaire;
use App\Entity\Typeschamps;
use App\Entity\RenderVous;
use App\Entity\PointVente;
use App\Entity\Departments;
use App\Entity\RequeteTableauBord;
use App\Entity\EntitiesPropriete;
use App\Entity\Entities;

class TableauBordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $listes_champs = $options['listes_champs'];
        foreach ($listes_champs as $key => $value) {
        }

        $builder->add("enregistrer_requete", CheckboxType::class, [
            'label'    => "Enregistrer la requête",
            'required' => false,
        ]);

        $builder->add("libelle", TextType::class, [
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' => 'Nom de la requête'
            ],
        ]);

        // this is the embeded form, the most important things are highlighted at the bottom
        $builder->add('requeteTableauBordFiltres', CollectionType::class, [
            'entry_type' => TableauBordConditionsType::class,
            'entry_options' => [
                'label' => false
            ],
            'label' => false,
            'by_reference' => false,
            // this allows the creation of new forms and the prototype too
            'allow_add' => true,
            // self explanatory, this one allows the form to be removed
            'allow_delete' => true
        ]);

        $builder->add('properties_entity_choix_champs', EntityType::class, [
            'class' => EntitiesPropriete::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->where('u.status = 0')
                    //->orderBy('u.libelle', 'ASC')
                    ;
            },
            'choice_label' => 'name',
            'expanded' => true,
            'multiple' => true,
            //'mapped' => false,
        ]);

        $builder->add('save', SubmitType::class, [
            'label' => 'Valider',
            'attr' => ['class' => 'btn btn-primary pull-left'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\RequeteTableauBord',
            'listes_champs' => null,
            'em'=>null,
        ));
    }

}
