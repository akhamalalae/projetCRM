<?php

namespace App\Form\Entreprises;

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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\User;
use App\Entity\CategorieProduits;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label' => 'Nom du produit',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('categories', EntityType::class, [
                'class' => CategorieProduits::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.libelle', 'ASC');
                },
                //'attr' => ['style' => 'display:none'],
                'choice_label' => 'libelle',
                'required' => false,
                'placeholder' => 'Catégories de produit',
                'label' => 'Catégories',
                'by_reference' => false,
                'attr' => ['class' => 'form-control js-multiple','multiple' => 'multiple'],
                'multiple'=>true,
                'expanded'=>false,
            ])
            ->add('status',CheckboxType::class,[
                'label' => 'Inactive',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Produit'
        ));
    }

}
