<?php

namespace App\Form\RenderVous;

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
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Formulaire;
use App\Entity\PointVente;
use App\Entity\Entreprise;
use App\Entity\User;

class RenderVousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,[
                'label' => 'Titre du rendez vous',
                'required' => true,
            ])
            ->add('start', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('end', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de Fin',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('description',TextareaType::class,[
                'label' => 'Description',
                'required' => false,
            ])
            ->add('all_day',CheckboxType::class, [
                'label' => 'Toute la journée',
                'required' => false,
            ])
            ->add('background_color', ColorType::class, [
                'label' => 'Couleur de l\'arrière plan',
                'required' => false,
            ])
            ->add('border_color', ColorType::class, [
                'label' => 'Couleur de la bordure',
                'required' => false,
            ])
            ->add('text_color', ColorType::class, [
                'label' => 'Couleur du texte',
                'required' => false,
            ])
            ->add('formulaire', EntityType::class, [
                'class' => Formulaire::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.libelle', 'ASC');
                },
                'choice_label' => 'libelle',
                'placeholder' => 'Choisir',
                'label' => 'Formulaire'
            ])
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.formeJuridique', 'ASC');
                },
                'choice_label' => 'formeJuridique',
                'placeholder' => 'Choisir',
                'label' => 'Entreprise'
            ])
            ->add('pointeVente', EntityType::class, [
                'class' => PointVente::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.libelle', 'ASC');
                },
                'choice_label' => 'libelle',
                'placeholder' => 'Choisir',
                'label' => 'Point de vente'
            ])
            ->add('intervenant', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.lastname', 'ASC');
                },
                'choice_label' => 'lastname',
                'placeholder' => 'Choisir',
                'label' => 'Intervenant'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\RenderVous'
        ]);
    }
}
