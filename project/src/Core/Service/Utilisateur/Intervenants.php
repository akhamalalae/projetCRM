<?php

namespace App\Core\Service\Utilisateur;

use App\Core\Interface\AddFlashInterface;
use App\Core\Interface\CreateFormInterface;
use App\Core\Interface\InitialisationInterface;
use App\Core\Interface\RenderInterface;
use App\Core\Interface\SubmittedFormInterface;
use App\Entity\User;
use App\Form\Intervenants\RegistrationFormType;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Intervenants implements InitialisationInterface, CreateFormInterface,
                        SubmittedFormInterface, RenderInterface, AddFlashInterface
{
    private array $intervenants;

    const VIEW_PATH         = 'intervenant/index.html.twig';
    const ROUTE             = 'intervenants';
    const TYPE_FLASH        = 'warning';
    const MESSAGE_FLASH     = 'Enregistrement effectué avec succès';

    public function __construct(public EntityManagerInterface $em,
        public MenuGenerator $menuGenerator,
        public UserPasswordEncoderInterface $passwordEncoder)
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
        $this->intervenants = $this->em->getRepository(User::class)->findBy([]);
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
        return [
            'menus'        => $this->menuGenerator->getMenu(),
            'intervenants' => $this->intervenants,
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
        return RegistrationFormType::class;
    }

    /**
     * Set name create form
     *
     * @return string
     */
    public function formName()
    {
        return 'registrationForm';
    }

    /**
     * Set data create form
     *
     * @return object|null
     */
    public function formData()
    {
        return  $this->createNewObject();
    }

    /**
     * Create new object
     *
     * @return object|null
     */
    public function createNewObject()
    {
        return new User();
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
        $form->getData()->setPassword(
            $this->passwordEncoder->encodePassword(
                $form->getData(),
                $form->get('plainPassword')->getData()
            )
        );

        $groupe = $form->get('groupe')->getData();
        foreach ($groupe as $value) {
            $form->getData->addGroupe($value);
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
