<?php

namespace App\Form\Referentiels;

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
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\User;

class ReferentielsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle',TextType::class,[
                'label' => 'Titre du referentiel',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('description',TextareaType::class,[
                'label' => 'Description du referentiel',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            // this is the embeded form, the most important things are highlighted at the bottom
            ->add('referentielsOptions', CollectionType::class, [
                'entry_type' => ReferentielsOptionsType::class,
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
            'data_class' => 'App\Entity\Referentiels'
        ));
    }

}
