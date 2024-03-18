<?php

namespace App\Form\Intervenants;

use App\Entity\GroupUsers;
use App\Entity\Ville;
use App\Entity\Region;
use App\Entity\Departement;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',TextType::class,[
                'label' => 'Email',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('lastname',TextType::class,[
                'label' => 'Nom',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('firstname',TextType::class,[
                'label' => 'Prenom',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])

            ->add('groupe', EntityType::class, array(
                'label' => 'Groupes',
                'class' => GroupUsers::class,
                'choice_label' => 'libelle',
                'placeholder' => 'Choisir ...',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                    ;
                },
                'by_reference' => false,
                'required' => false,
                'attr' => ['class' => 'js-multiple','multiple' => 'multiple'],
                'multiple'=>true,
                'mapped' => false,
                'expanded'=>false,
            ))

            ->add('roles', ChoiceType::class, [
                'choices'  => [
                    'Gestionnaire' => 'ROLE_GESTIONNAIRE',
                    'Intervenant' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'required' => true,
        ]);

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($tagsAsArray) {
                    // transform the array to a string
                    return implode(', ', $tagsAsArray);
                },
                function ($tagsAsString) {
                    // transform the string back to an array
                    return explode(', ', $tagsAsString);
                }
        ));

        $builder->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirme Password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
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
        $resolver->setDefaults([
            'data_class' => 'App\Entity\User'
        ]);
    }
}
