<?php

namespace App\Form\Formulaires;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\User;
use App\Entity\Entreprise;

class FormulaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle',TextType::class,[
                'label' => 'Titre',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('description',TextareaType::class,[
                'label' => 'Description',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateDebut', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début de l\'utilisation du formulaire',
                'attr' => ['class' => 'datepicker date'],
            ])
            ->add('dateFin', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de Fin de l\'utilisation du formulaire',
            ])
            // this is the embeded form, the most important things are highlighted at the bottom
            ->add('champFormulaire', CollectionType::class, [
                'entry_type' => ChampsFormulaireType::class,
                'entry_options' => [
                    'label' => false
                ],
                'label' => false,
                'by_reference' => false,
                // this allows the creation of new forms and the prototype too
                'allow_add' => true,
                // self explanatory, this one allows the form to be removed
                'allow_delete' => true
            ])
            ->add('intervenants', EntityType::class, array(
                'label' => 'Intervenants',
                'class' => User::class,
                'choice_label' => 'lastname',
                'group_by' => 'groupeString',
                'placeholder' => 'Choisir les Intervenants',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                    //->innerJoin('u.user', 'user')
                    //->where('u.roles = :rol')
                    //->setParameter('rol', '["ROLE_USER"]')
                    ;
                },
                'by_reference' => false,
                'attr' => ['class' => 'js-multiple','multiple' => 'multiple'],
                'multiple'=>true,
                'expanded'=>false,
            ));
            $builder
            //entreprises
            ->add('entreprises', EntityType::class, array(
                'label' => 'Entreprises',
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
            ));

            $builder
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
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Formulaire'
        ));
    }

}
