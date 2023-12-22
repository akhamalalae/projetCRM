<?php

namespace App\Core\Service\EnregistrementFormulaire;

use App\Core\Interface\InitialisationInterface;
use App\Entity\ChampsFormulaire;
use App\Entity\EnregistrementFormulaire;
use App\Entity\Formulaire;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;

class Telecharger implements InitialisationInterface
{
    private int    $id;
    private object $formulaire;
    private object $enregistrementFormulaire;
    private string $fields = '';
    private string $lineData = '';
    private string $format = '';

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
        $this->id     = $param['id'];
        $this->format = $param['format'];

        $this->formulaire = $this->em->getRepository(Formulaire::class)->find($this->id);

        $this->enregistrementFormulaire = $this->em->getRepository(EnregistrementFormulaire::class)->findBy(
            ['formulaires' => $this->formulaire]
        );
    }

    /**
     * Format Excel
     *
     * @return array
     */
    public function telecharger()
    {
        $this->getFieldsAndLineData();

        return [
            'resultat' => $this->fields."\n".$this->lineData,
            'fileName' => $this->getNameFile()
        ];
    }

    /**
     * getFields And LineData
     *
     * @return void
     */
    public function getFieldsAndLineData()
    {
        foreach ($this->enregistrementFormulaire[0]->getResultats() as $key => $value) {
            $champsformulaire = $this->em->getRepository(ChampsFormulaire::class)->find($key);
            $this->fields = $this->fields.$champsformulaire->getLibelle().",";
        }

        foreach ($this->enregistrementFormulaire as $key => $value) {
            foreach ($value->getResultats() as $valueEnregistrementFormulaire) {
                if(is_array($valueEnregistrementFormulaire)){
                    $valueEnregistrementFormulaire = $valueEnregistrementFormulaire["date"];
                }
                $this->lineData = $this->lineData.$valueEnregistrementFormulaire.",";
            }
            $this->lineData .="\n";
        }
    }

    /**
     * File Format
     *
     * @return string
     */
    public function getNameFile()
    {
        return "resultats_formulaire_".$this->formulaire->getLibelle()."_".date('Y-m-d') . "." .$this->format;
    }
}
