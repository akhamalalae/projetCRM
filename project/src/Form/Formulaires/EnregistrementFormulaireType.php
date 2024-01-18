<?php

namespace App\Form\Formulaires;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class EnregistrementFormulaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $champsFormulaires = $options['champsFormulaires'];

        foreach ($champsFormulaires as $champ) {
            if($champ->getStatus() == false){
                $nomChamp = $champ->getLibelle();
                $typeChamp = $champ->getType()->getLibelle();
                $typeChampId = $champ->getType()->getId();
                $champId = $champ->getId();
                $listeOptionseferentiels = array();
                if($champ->getReferentiels()){
                    $referentiels = $champ->getReferentiels();
                    $referentielsOptions = $referentiels->getReferentielsOptions();
                    foreach ($referentielsOptions as $options) {
                        $listeOptionseferentiels[$options->getLibelle()] = $options->getLibelle();
                    }
                }
                //Récuperer les options du champ
                $champOptions = $champ->getOptions();
                $arrayOptios = array();
                foreach ($champOptions as $option) {
                    $arrayOptios[$option->getLibelle()] = $option->getLibelle();
                }

                if ($typeChampId == 1) {
                    $builder->add($champId,TextType::class,[
                        'label' => $nomChamp,
                        'required' => true,
                    ]);
                }elseif ($typeChampId == 7) {
                    $builder->add($champId,TextType::class,[
                        'label' => $nomChamp,
                        'required' => true,
                    ]);
                }elseif($typeChampId == 2){
                    $builder->add($champId,TextareaType::class,[
                        'label' => $nomChamp,
                        'required' => false,
                    ]);
                }elseif($typeChampId == 3){
                    $builder->add($champId,IntegerType::class,[
                        'label' => $nomChamp,
                        'required' => true,
                    ]);
                }elseif($typeChampId == 5){
                    $builder->add($champId,ChoiceType::class,[
                        'label' => $nomChamp,
                        'choices'  => $arrayOptios,
                    ]);
                }elseif($typeChampId == 11){
                    $builder->add($champId,ChoiceType::class,[
                        'label' => $nomChamp,
                        'choices' => array(
                            'Oui' => true,
                            'Non' => false,
                         ),
                         'label' => $nomChamp,
                         'required' => true,
                    ]);
                }elseif($typeChampId == 9){
                    $builder->add($champId,DateTimeType::class,[
                        'widget' => 'single_text',
                        'label' => $nomChamp,
                        'attr' => ['class' => 'form-control'],
                        'required' => true,
                    ]);
                }elseif($typeChampId == 6){
                    if ($listeOptionseferentiels) {
                        $builder->add($champId, ChoiceType::class, [
                            'choices'  => $listeOptionseferentiels,
                            'label' => $nomChamp,
                            'required' => true,
                        ]);
                    }
                }elseif($typeChampId == 4){
                    $builder->add($champId,MoneyType::class,[
                        'label' => $nomChamp,
                    ]);
                }elseif($typeChampId == 10){
                    $builder->add("files_".$champId, FileType::class, [
                        'multiple' => true,
                        'label' => $nomChamp,
                        'attr'     => [
                            //'accept' => 'image/*',
                            'multiple' => 'multiple'
                        ]
                        ]);
                }
            }
        }

        $builder->add('save', SubmitType::class, [
            'label' => 'Valider',
            'attr' => ['class' => 'btn btn-primary pull-right'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'champsFormulaires'=>null,
        ]);
    }
}
