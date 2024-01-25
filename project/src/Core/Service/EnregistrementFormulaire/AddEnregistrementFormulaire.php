<?php

namespace App\Core\Service\EnregistrementFormulaire;

use App\Core\Interface\AddFlashInterface;
use App\Core\Interface\CreateFormInterface;
use App\Core\Interface\InitialisationInterface;
use App\Core\Interface\RenderInterface;
use App\Core\Interface\SubmittedFormInterface;
use App\Entity\ChampsFormulaire;
use App\Entity\EnregistrementFormulaire;
use App\Entity\Files;
use App\Entity\RenderVous;
use App\Form\Formulaires\EnregistrementFormulaireType;
use App\Services\MenuGenerator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class AddEnregistrementFormulaire implements InitialisationInterface, CreateFormInterface,
                    SubmittedFormInterface, RenderInterface, AddFlashInterface
{
    private int     $id;
    private object  $user;
    private object  $formulaire;
    private object  $rendezVous;
    private string  $directory;
    private object  $enregistrementFormulaire;
    private array   $datachampsFormulaires = [];
    private array   $resultats = [];

    const VIEW_PATH         = 'enregistrementFormulaire/index.html.twig';
    const ROUTE             = 'calendar_vue_agenda';
    const TYPE_FLASH        = 'warning';
    const MESSAGE_FLASH     = 'Enregistrement effectué avec succès';
    const FILE              = 'files';
    const OUI               = 'oui';
    const NON               = 'non';
    const DATE_FORMAT       = 'Y-m-d H:i:s';

    public function __construct(public EntityManagerInterface $em, public MenuGenerator $menuGenerator)
    {
    }

    /**
     * Initialisation
     *
     * @param array $params
     * 
     * @return void
     */
    public function init($param): void
    {
        $this->id        = $param['id'];
        $this->user      = $param['user'];
        $this->directory = $param['directory'];

        $this->rendezVous = $this->em->getRepository(RenderVous::class)->find($this->id);

        $this->formulaire = $this->rendezVous->getFormulaire();

        $this->datachampsFormulaires = $this->em->getRepository(ChampsFormulaire::class)->findBy(
            ['formulaire' => $this->formulaire, 'status' => 0],
            ['ordre' => 'ASC']
        );
    }

    //RenderInterface

    /**
     * view
     *
     * @return string
     */
    public function view(): string
    {
        return self::VIEW_PATH;
    }

    /**
     * parameters
     *
     * @return array
     */
    public function parameters(): array
    {
        return [
            'menus'      => $this->menuGenerator->getMenu(),
            'formulaire' => $this->formulaire
        ];
    }

    //CreateFormInterface

    /**
     * Set type create form
     *
     * @return string
     */
    public function formType(): string
    {
        return EnregistrementFormulaireType::class;
    }

    /**
     * Set name create form
     *
     * @return string
     */
    public function formName(): string
    {
        return 'form';
    }

    /**
     * Set data create form
     *
     * @return object|null
     */
    public function formData(): object|null
    {
        return null;
    }

    /**
     * Create new object
     *
     * @return object|null
     */
    public function createNewObject(): object
    {
        return new EnregistrementFormulaire();
    }

    /**
     * Set options create form
     *
     * @return array
     */
    public function formOptions(): array
    {
        return ['champsFormulaires' => $this->datachampsFormulaires];
    }

    //SubmittedFormInterface

    /**
     * Save form data
     *
     * @param Form $form
     *
     * @return void
     */
    public function save($form): void
    {
        $this->saveSpecific($form);

        $this->rendezVous->setEffectuer(true);

        $this->em->persist($this->rendezVous);
        $this->em->flush();
    }

    /**
     * Save specific data
     *
     * @param Form $form
     *
     * @return void
     */
    public function saveSpecific($form): void
    {
        $this->enregistrementFormulaire = $this->createNewObject();

        $this->getResultats($form);

        $this->enregistrementFormulaire->setDateCreation(new DateTime())
            ->setDateModification(new DateTime())
            ->setFormulaires($this->formulaire)
            ->setIntervenant($this->user)
            ->setResultats($this->resultats)
            ->setCalanderRendezVous($this->rendezVous);

        $this->em->persist($this->enregistrementFormulaire);
        $this->em->flush();
    }

    /**
     * Save
     * 
     * @return void
     */
    public function beforeSave(): void
    {
    }

     /**
     * Save
     *
     * @return void
     */
    public function afterSave(): void
    {
    }

    //RedirectToRouteInterface

    /**
     * Name route
     *
     * @return string
     */
    public function route(): string
    {
        return self::ROUTE;
    }

    /**
     * parameter sRoute
     *
     * @return array
     */
    public function parametersRoute(): array
    {
        return [];
    }

    //AddFlashInterface

    /**
     * Type Flash
     *
     * @return string
     */
    public function type(): string
    {
        return self::TYPE_FLASH;
    }

    /**
     * Message Flash
     *
     * @return string
     */
    public function message(): string
    {
        return self::MESSAGE_FLASH;
    }

    /**
     * récupérer le résultat
     *
     * @param Form $fom
     * 
     * @return void
     */
    public function getResultats($form): void
    {
        foreach ($form->getData() as $champId => $value) {
            $isFile = '';
            if (str_contains($champId, self::FILE)) {
                //cas champs files
                $explode = explode("_", $champId);
                $champId = $explode[1];
                $isFile  = $explode[0];
            }

            $champFormulaire = $this->em->getRepository(ChampsFormulaire::class)->find($champId);

            if ($champFormulaire) {
                if ($isFile === self::FILE) {
                    $fichiers = $form->get($isFile . '_' . $champId)->getData();
                    $this->addFiles($fichiers, $champFormulaire);
                    $this->resultats[$champId] = self::FILE;
                }

                if ($isFile !== self::FILE) {
                   $this->formatResultats($champId, $value);
                }
            }
        }
    }

    /**
     * formater le résultat
     *
     * @param int $champId
     * @param mixed $form
     *
     * @return void
     */
    public function formatResultats(int $champId, mixed $value): void
    {
        switch ($value) {
            case $value === true:
                $this->resultats[$champId] = self::OUI;
                break;
            case $value === false:
                $this->resultats[$champId] = self::NON;
                break;
            case is_object($value) && ($value instanceof DateTime):
                $this->resultats[$champId] = $value->format(self::DATE_FORMAT);
                break;
            default:
                $this->resultats[$champId] = $value;
        }
    }

    /**
     * ajouter les fichiers
     *
     * @param array             $fichiers
     * @param ChampsFormulaire  $champFormulaire
     *
     * @return void
     */
    public function addFiles(array $fichiers, ChampsFormulaire $champFormulaire): void
    {
        foreach($fichiers as $fichier) {
            $hashFichier = md5(uniqid()).'.'.$fichier->guessExtension();
            $fichier->move($this->directory, $hashFichier);

            $file = new Files();

            $file->setDateCreation(new DateTime())
                ->setDateModification(new DateTime())
                ->setFile($hashFichier)
                ->setChampsFormulaire($champFormulaire)
                ->setName($fichier->getClientOriginalName());

            $this->enregistrementFormulaire->addFile($file);
        }
    }
}
