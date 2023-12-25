<?php

namespace App\Core\Service\Formulaire;

use App\Core\Interface\AddFlashInterface;
use App\Core\Interface\CreateFormInterface;
use App\Core\Interface\InitialisationInterface;
use App\Core\Interface\RenderInterface;
use App\Core\Interface\SubmittedFormInterface;
use App\Entity\Formulaire;
use App\Form\Formulaires\FormulaireType;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class AddEditeFormulaire implements InitialisationInterface, CreateFormInterface,
                        SubmittedFormInterface, RenderInterface, AddFlashInterface
{
    private int             $id = 0;
    private object          $user;
    private object          $dataFormulaire;
    private ArrayCollection $orignalOptions;

    const VIEW_PATH_ADD     = 'formulaire/addFormulaire.html.twig';
    const VIEW_PATH_EDITE   = 'formulaire/editFormulaire.html.twig';
    const CURRENT_PAGE      = 'add_formulaire';
    const ROUTE             = 'formulaire';
    const TYPE_FLASH        = 'warning';
    const MESSAGE_FLASH     = 'Enregistrement effectué avec succès';

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
        $this->user                 = $param['user'];

        if($this->id !== 0) {
            $this->dataFormulaire   = $this->em->getRepository(Formulaire::class)->find($this->id);

            $this->orignalOptions = new ArrayCollection();
            foreach ($this->dataFormulaire->getChampFormulaire() as $champ) {
                $this->orignalOptions->add($champ);
            }
        }

        if($this->id === 0) {
            $this->dataFormulaire   = $this->createNewObject();
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
        if($this->id !== 0) {
            return self::VIEW_PATH_EDITE;
        }

        return self::VIEW_PATH_ADD;
    }

    /**
     * parameters
     *
     * @return array
     */
    public function parameters()
    {
        $listeFormulaires = $this->em->getRepository(Formulaire::class)->findBy(
            ['user' => $this->user,'status' => 0]
        );

        return [
            'menus'             => $this->menuGenerator->getMenu(),
            'current_page'      => self::CURRENT_PAGE,
            'countformulaires'  => count($listeFormulaires),
            'champsFormulaire'  => $this->dataFormulaire ->getChampFormulaire(),
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
        return FormulaireType::class;
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
        if($this->id !== 0) {
            return $this->dataFormulaire;
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
        return new Formulaire();
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
        $form->getData()->setUser($this->user);
        $form->getData()->setStatus(0);

        foreach ($this->orignalOptions as $champ) {
            if ($form->getData()->getChampFormulaire()->contains($champ) === false) {
                $this->em->remove($champ);
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
        return [];
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
}
