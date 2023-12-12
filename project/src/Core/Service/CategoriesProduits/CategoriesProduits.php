<?php

namespace App\Core\Service\CategoriesProduits;

use App\Core\Interface\CreateFormInterface;
use App\Core\Interface\InitialisationInterface;
use App\Core\Interface\RenderInterface;
use App\Core\Interface\SubmittedFormInterface;
use App\Entity\CategorieProduits;
use App\Form\Entreprises\CategorieProduitsType;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;

class CategoriesProduits implements CreateFormInterface, SubmittedFormInterface,
                        RenderInterface, InitialisationInterface
{
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
    }

    //RenderInterface

    /**
     * Save form data
     *
     * @return string
     */
    public function view()
    {
        return 'entrepriseProduits/categoriesProduits.html.twig';
    }

    /**
     * current_page
     *
     * @return string
     */
    public function getCurrentPage()
    {
        return 'categoriesProduits';
    }

    /**
     * Save specific data
     *
     * @return array
     */
    public function parameters()
    {
        $listeCategorieProduits = $this->em->getRepository(CategorieProduits::class)->findAll();

        return [
            'menus' => $this->menuGenerator->getMenu(),
            'current_page' => $this->getCurrentPage(),
            'listeCategorieProduits' => $listeCategorieProduits,
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
        return CategorieProduitsType::class;
    }

    /**
     * Set data create form
     *
     * @return object|null
     */
    public function formData()
    {
        return $this->createNewObject();
    }

    /**
     * Create new object
     *
     * @return object|null
     */
    public function createNewObject()
    {
        return new CategorieProduits();
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
        return 'categoriesProduits';
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
        return 'warning';
    }

    /**
     * Message Flash
     *
     * @return string
     */
    public function message()
    {
        return 'Enregistrement effectué avec succès ';
    }
}
