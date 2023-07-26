<?php

namespace App\Form\TableauBord;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\RequeteTableauBord;

class ChoixTableauBordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('RequeteTableauBord', EntityType::class, array(
            'label' => 'Tableau de Bord',
            'class' => RequeteTableauBord::class,
            'choice_label' => 'libelle',
            'placeholder' => 'Choisir un tableaude bord',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                //->innerJoin('u.user', 'user')
                //->where('u.roles = :rol')
                //->setParameter('rol', '["ROLE_USER"]')
                ;
            },
            //'mapped' => false,
            'required' => false,
        ));

        $builder->add('choix', ChoiceType::class, [
            'choices'  => [
                'Créer un nouveau tableau de Bord' => 0,
                'Charger un tableau de Bord' => 1,
            ],
            'label' => 'Voulez vous ',
            'required' => true,
            //'mapped' => false,
        ]);

        $builder->add('save', SubmitType::class, [
            'label' => 'Valider',
            'attr' => ['class' => 'btn btn-primary pull-left'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            //'data_class' => 'App\Entity\RequeteTableauBord'
        ]);
    }

}
