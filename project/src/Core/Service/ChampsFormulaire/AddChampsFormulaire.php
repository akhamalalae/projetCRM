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
    private int|null        $champ = null;

    const VIEW_PATH         =  'champsFormulaire/champsFormulaire.html.twig';
    const CURRENT_PAGE      =  'listeChampsFormulaire';
    const ROUTE             =  'formulaire_champs';
    const TYPE_FLASH        =  'warning';
    const MESSAGE_FLASH     =  'Enregistrement effectué avec succès';

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

        if($this->id !== 0) {
            $this->dataFormulaire   = $this->em->getRepository(Formulaire::class)->find($this->id);
        }

        if($this->champ !== null) {
            $this->datachampsFormulaire = $this->getChampFormulaire();
        }

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
        return self::VIEW_PATH;
    }

    /**
     * parameters
     *
     * @return array
     */
    public function parameters()
    {
        if($this->id !== 0) {
            $listechampsFormulaires = $this->em->getRepository(ChampsFormulaire::class)->findBy(
                ['formulaire' => $this->id,'status' => 0]
            );
        }

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
            'current_page'           => self::CURRENT_PAGE,
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
     * Set name create form
     *
     * @return string
     */
    public function formName()
    {
        return 'form';
    }

    /**
     * Set data create form
     *
     * @return object|null
     */
    public function formData()
    {
        if($this->champ !== null) {
            return $this->getChampFormulaire();
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
        $form->getData()->setFormulaire($this->dataFormulaire)
            ->setStatus(0)
            ->setDateCreation(new DateTime())
            ->setDateModification(new DateTime())
        ;

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
    public function beforeSave()
    {
    }

     /**
     * Save
     *
     * @return void
     */
    public function afterSave()
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
        return self::ROUTE;
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
        return self::TYPE_FLASH;
    }

    /**
     * Message Flash
     *
     * @return string
     */
    public function message()
    {
        return self::MESSAGE_FLASH;
    }

    /**
     * get champ formulaire
     *
     * @return ChampsFormulaire
     */
    public function getChampFormulaire():ChampsFormulaire
    {
        return $this->em->getRepository(ChampsFormulaire::class)->find($this->champ);
    }
}
