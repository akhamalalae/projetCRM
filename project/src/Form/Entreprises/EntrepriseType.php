<?php

namespace App\Form\Entreprises;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Ville;
use App\Entity\Region;
use App\Entity\Departement;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('formeJuridique',TextType::class,[
                'label' => 'Forme Juridique',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('nomsCommerciaux',TextType::class,[
                'label' => 'Nom',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('numeroSIREN',TextType::class,[
                'label' => 'SIREN',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('NumeroSIRET',TextType::class,[
                'label' => 'SIRET',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('numerosRCS',TextType::class,[
                'label' => 'Numeros RCS',
                'attr' => ['class' => 'form-control'],
                'required' => true,
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
            ])
            ->add('dateCreationEntreprise', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date création entreprise',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('dateImmatriculationRCS', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date immatriculation RCS',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('dateEnregistrementINSEE', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date enregistrement INSEE',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('capitalSocial',TextType::class,[
                'label' => 'Capital social',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            // this is the embeded form, the most important things are highlighted at the bottom
            ->add('produits', CollectionType::class, [
                'entry_type' => ProduitType::class,
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

            ->add('pointVentes', CollectionType::class, [
                'entry_type' => PointVenteType::class,
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

            ->add('save', SubmitType::class, [
                'label' => 'Valider',
                'attr' => ['class' => 'btn btn-primary pull-left'],
            ])
        ;

        $builder->add('region', EntityType::class, [
                'class' => Region::class,
                'placeholder' => 'Sélectionnez votre région',
                'mapped'      => false,
                'required'    => false
            ]);

        $builder->get('region')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
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
            'data_class' => 'App\Entity\Entreprise'
        ));
    }

}
