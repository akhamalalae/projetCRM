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
use App\Entity\Formulaire;
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
    private object  $dataFormulaire;
    private object  $rendezVous;
    private string  $directory;
    private object  $dataenregistrementFormulaire;
    private array   $datachampsFormulaires = [];
    private array   $resultats = [];

    const VIEW_PATH         = 'enregistrementFormulaire/index.html.twig';
    const ROUTE             = 'calendar_vue_agenda';
    const TYPE_FLASH        = 'warning';
    const MESSAGE_FLASH     = 'Enregistrement effectué avec succès';

    public function __construct(public EntityManagerInterface $em, public MenuGenerator $menuGenerator)
    {
    }

    /**
     * Initialisation
     *
     * @param array $params
     * @return void
     */
    public function init($param)
    {
        $this->id        = $param['id'];
        $this->user      = $param['user'];
        $this->directory = $param['directory'];
        
        $this->datachampsFormulaires = $this->em->getRepository(ChampsFormulaire::class)->findBy(
            ['formulaire' => $this->id,'status' => 0], ['ordre' => 'ASC']
        );
        $this->rendezVous = $this->em->getRepository(RenderVous::class)->find($this->id);
        $this->dataFormulaire = $this->em->getRepository(Formulaire::class)->find($this->rendezVous->getFormulaire());
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
            'menus'      => $this->menuGenerator->getMenu(),
            'formulaire' => $this->dataFormulaire
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
        return EnregistrementFormulaireType::class;
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
        return $this->datachampsFormulaires;
    }

    /**
     * Create new object
     *
     * @return object|null
     */
    public function createNewObject()
    {
        return new EnregistrementFormulaire();
    }

     /**
     * Set options create form
     *
     * @return array
     */
    public function formOptions()
    {
        return ['champsFormulaires' => $this->datachampsFormulaires];
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
    }

    /**
     * Save specific data
     *
     * @param Form $form
     * @return void
     */
    public function saveSpecific($form)
    {
        $this->dataenregistrementFormulaire = $this->createNewObject();
        $resultats = $this->getResultats($form, $this->datachampsFormulaires);
        $this->dataenregistrementFormulaire->setDateCreation(new DateTime())
            ->setDateModification(new DateTime())
            ->setFormulaires($this->dataFormulaire)
            ->setIntervenant($this->user)
            ->setResultats($resultats)
            ->setCalanderRendezVous($this->rendezVous);

        $this->em->persist($this->dataenregistrementFormulaire);

        $this->rendezVous->setEffectuer(true);
        $this->em->persist($this->rendezVous);

        $this->em->flush();
    }

    /**
     *
     * @param Form   $form
     * @param array  $champsFormulaires
     *
     * @return array
     */
    public function getResultats($form, $champsFormulaires): array
    {
        foreach ($form->getData() as $key => $value) {
            $isFile = '';
            if (str_contains($key, 'files')) {
                //cas champs files
                $explodeChampId = explode("_", $key);
                $key = $explodeChampId[1];
                $isFile = $explodeChampId[0];
            }
            $this->uplayResultats($form, $champsFormulaires, $key, $value, $isFile);
        }

        return $this->resultats;
    }

     /**
     * @return void
     */
    public function uplayResultats($form, $champsFormulaires, $key, $value, $isFile)
    {
        foreach ($champsFormulaires as $champ) {
            $champId = $champ->getId();
            if ($champId == $key) {
                if ($isFile == 'files') {
                    $listeFichiers = $form->get($isFile.'_'.$key)->getData();
                    foreach($listeFichiers as $un_fichier) {
                        $fichier = md5(uniqid()).'.'.$un_fichier->guessExtension();

                        $un_fichier->move($this->directory, $fichier);

                        $file = new Files();
                        $file->setDateCreation(new DateTime());
                        $file->setDateModification(new DateTime());
                        $file->setFile($fichier);
                        $file->setChampsFormulaire($this->em->getRepository(ChampsFormulaire::class)->find($key));
                        $file->setName($un_fichier->getClientOriginalName());
                        $this->dataenregistrementFormulaire->addFile($file);
                    }
                    $this->resultats[$champId] = "files";
                }else {
                    if (is_object($value) && ($value instanceof DateTime) ) {
                        $this->resultats[$champId] = $value->format('Y-m-d H:i:s');
                    }else {
                        if($value === true) {
                            $this->resultats[$champId] = "OUI";
                        }elseif($value === false) {
                            $this->resultats[$champId] = "NON";
                        }else {
                            $this->resultats[$champId] = $value;
                        }
                    }
                }
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
