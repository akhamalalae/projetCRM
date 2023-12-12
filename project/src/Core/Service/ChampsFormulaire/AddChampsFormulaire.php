<?php

namespace App\Core\Service\ChampsFormulaire;

use App\Core\Interface\AddFlashInterface;
use App\Core\Interface\CreateFormInterface;
use App\Core\Interface\InitialisationInterface;
use App\Core\Interface\RenderInterface;
use App\Core\Interface\SubmittedFormInterface;
use App\Entity\ChampsFormulaire;
use App\Entity\Formulaire;
use App\Entity\Typeschamps;
use App\Form\Formulaires\ChampsFormulaireType;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

class AddChampsFormulaire implements InitialisationInterface, CreateFormInterface,
                        SubmittedFormInterface, RenderInterface, AddFlashInterface
{
    private int             $id = 0;
    private bool            $statusOptionsChamps  = false;
    private bool            $editChamps  = false;
    private object          $dataFormulaire;
    private object          $datachampsFormulaire;
    private string          $choiceType;
    private ArrayCollection $orignalOptions;
    private                 $champ;

    public function __construct(public EntityManagerInterface $em, public MenuGenerator $menuGenerator)
    {
    }

    //InitialisationInterface

    /**
     * Initialisation
     *
     * @param array $params
     * @return void
     */
    public function init($param)
    {
        $this->id                   = $param['id'];
        $this->champ                = $param['champ'];
        $this->choiceType           = Typeschamps::CHOICETYPE;
        $this->dataFormulaire       = $this->em->getRepository(Formulaire::class)->find($this->id);
        $this->datachampsFormulaire = $this->em->getRepository(ChampsFormulaire::class)->find($this->champ);

        $this->orignalOptions = new ArrayCollection();
        foreach ($this->datachampsFormulaire->getOptions() as $champ) {
            $this->orignalOptions->add($champ);
        }
    }

    //RenderInterface

    /**
     * view
     *
     * @return string
     */
    public function view()
    {
        return 'champsFormulaire/champsFormulaire.html.twig';
    }

    /**
     * parameters
     *
     * @return array
     */
    public function parameters()
    {
        $listechampsFormulaires = $this->em->getRepository(ChampsFormulaire::class)->findBy(
            ['formulaire' => $this->id,'status' => 0]
        );

        if($this->champ !== null) {
            $this->editChamps = true;
            if ($this->datachampsFormulaire?->getType()->getId() === $this->choiceType) {
                $this->statusOptionsChamps = true;
            }
        }

        return [
            'menus'                  => $this->menuGenerator->getMenu(),
            'statusOptionsChamps'    => $this->statusOptionsChamps,
            'editChamps'             => $this->editChamps,
            'formulaire'             => $this->dataFormulaire,
            'countChampsFormulaires' => count($listechampsFormulaires),
            'current_page'           => 'listeChampsFormulaire',
            'champsFormulaires'      => $listechampsFormulaires
        ];
    }

    //CreateFormInterface

    /**
     * Set type create form
     *
     * @return string
     */
    public function formType()
    {
        return ChampsFormulaireType::class;
    }

    /**
     * Set data create form
     *
     * @return object|null
     */
    public function formData()
    {
        if($this->champ != null) {
            return $this->em->getRepository(ChampsFormulaire::class)->find($this->champ);
        }

        return  $this->createNewObject();
    }

    /**
     * Create new object
     *
     * @return object|null
     */
    public function createNewObject()
    {
        return new ChampsFormulaire();
    }

     /**
     * Set options create form
     *
     * @return array
     */
    public function formOptions()
    {
        return [];
    }

    /**
     * Set options create form
     *
     * @return array
     */
    public function formOtherOptions()
    {
        return [];
    }

    //SubmittedFormInterface

    /**
     * Save form data
     *
     * @param Form $form
     * @return void
     */
    public function save($form)
    {
        $this->saveSpecific($form);
        $this->em->persist($form->getData());
        $this->em->flush();
    }

    /**
     * Save specific data
     *
     * @param Form $form
     * @return void
     */
    public function saveSpecific($form)
    {
        $form->getData()->setFormulaire($this->dataFormulaire);
        $form->getData()->setStatus(0);
        $form->getData()->setDateCreation(new DateTime());
        $form->getData()->setDateModification(new DateTime());

        foreach ($this->orignalOptions as $champ) {
            if ($form->getData()->getOptions()->contains($champ) === false) {
                $this->em->remove($champ);
            }
        }

        if ($this->editChamps === true && $form->getData()->getType()->getId() !== $this->choiceType) {
            foreach ($form->getData()->getOptions() as $option) {
                $this->em->remove($option);
            }
        }
    }

    /**
     * Save
     * @return void
     */
    public function saveBeforeSubmitFormData()
    {
    }

    //RedirectToRouteInterface

    /**
     * Name route
     *
     * @return string
     */
    public function route()
    {
        return 'formulaire_champs';
    }

    /**
     * parametersRoute
     *
     * @return array
     */
    public function parametersRoute()
    {
        return ['id' => $this->id, 'champ' => null];
    }

    //AddFlashInterface

    /**
     * Type Flash
     *
     * @return string
     */
    public function type()
    {
        return 'warning';
    }

    /**
     * Message Flash
     *
     * @return string
     */
    public function message()
    {
        return 'Enregistrement effectué avec succès';
    }
}
