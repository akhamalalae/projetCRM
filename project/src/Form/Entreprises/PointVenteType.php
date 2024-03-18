<?php

namespace App\Form\Entreprises;

use App\Entity\Ville;
use App\Entity\Region;
use App\Entity\Departement;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PointVenteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle',TextType::class,[
                'label' => 'Titre',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ]);

        $builder
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'placeholder' => 'Sélectionnez votre région',
                'attr' => ['onchange' => 'myFunctionType(0)'],
                'mapped'      => false,
                'required'    => true
            ]);

            $builder
            ->add('departement', EntityType::class, [
                'class' => Departement::class,
                'placeholder' => 'Sélectionnez votre département',
                'mapped'      => false,
                'required'    => true
            ]);

            $builder
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'placeholder' => 'Sélectionnez votre ville',
                'mapped'      => false,
                'required'    => true
            ])
            ->add('adresse',TextType::class,[
                'label' => 'Adresse',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('complementAdresse',TextareaType::class,[
                'label' => 'Complément adresse',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ]);

            $builder->add('status',CheckboxType::class,[
                'label' => 'Inactive',
                'required' => false,
            ]);


        $builder->get('region')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $this->addDepartementField($form->getParent(), $form->getData());
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $data = $event->getData();
                if ($data) {
                    $ville = $data->getVille();
                    $form = $event->getForm();
                    if ($ville) {
                        $departement = $ville->getDepartement();
                        $region = $departement->getRegion();
                        $this->addDepartementField($form, $region);
                        $this->addVilleField($form, $departement);
                        $form->get('region')->setData($region);
                        $form->get('departement')->setData($departement);
                    } else {
                        $this->addDepartementField($form, null);
                        $this->addVilleField($form, null);
                    }
                }
            }
        );

    }


    /**
     * Rajoute un champs departement au formulaire
     * @param FormInterface $form
     * @param Region $region
     */
    private function addDepartementField(FormInterface $form, ?Region $region)
    {
        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'departement',
            EntityType::class,
            null,
            [
                'class' => Departement::class,
                'placeholder'     => $region ? 'Sélectionnez votre département' : 'Sélectionnez votre région',
                'mapped'          => false,
                'required'        => false,
                'auto_initialize' => false,
                'choices'         => $region ? $region->getDepartements() : []
            ]
        );
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $this->addVilleField($form->getParent(), $form->getData());
            }
        );
        $form->add($builder->getForm());
    }

    /**
     * Rajoute un champs ville au formulaire
     * @param FormInterface $form
     * @param Departement $departement
     */
    private function addVilleField(FormInterface $form, ?Departement $departement)
    {
        $form->add('ville', EntityType::class, [
            'class' => Ville::class,
            'placeholder' => $departement ? 'Sélectionnez votre ville' : 'Sélectionnez votre département',
            'choices'     => $departement ? $departement->getVilles() : []
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\PointVente'
        ));
    }

}
