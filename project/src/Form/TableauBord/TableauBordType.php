<?php

namespace App\Form\TableauBord;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\EntitiesPropriete;

class TableauBordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
            'data_class' => 'App\Entity\RequeteTableauBord'
        ));
    }

}
