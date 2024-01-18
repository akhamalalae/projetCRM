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
    private array  $enregistrement;
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
        $this->id             = $param['id'];
        $this->format         = $param['format'];
        $this->formulaire     = $this->em->getRepository(Formulaire::class)->find($this->id);
        $this->enregistrement = $this->em->getRepository(EnregistrementFormulaire::class)->findBy(
            ['formulaires' => $this->formulaire]
        );
    }

    /**
     * Response content
     *
     * @return string
     */
    public function content(): string
    {
        return $this->telecharger()['resultat'];
    }

    /**
     * Response status
     *
     * @return int
     */
    public function status(): int
    {
        return 200;
    }

    /**
     * Headers status
     *
     * @return array
     */
    public function headers(): array
    {
        return [
            'Content-Type' => 'application/vnd.ms-excel',
            "Content-disposition" => "attachment; filename=".$this->telecharger()['fileName']
        ];
    }

    /**
     * Format Excel
     *
     * @return array
     */
    public function telecharger(): array
    {
        return [
            'resultat' => $this->getResults(),
            'fileName' => $this->getNameFile()
        ];
    }

    /**
     * get Results
     *
     * @return void
     */
    public function getResults(): string
    {
        $this->getFields();
        $this->getLineData();

        return $this->fields."\n".$this->lineData;
    }

    /**
     * get Fields
     *
     * @return void
     */
    public function getFields(): void
    {
        foreach ($this->enregistrement[0]->getResultats() as $key => $value) {
            $champsformulaire = $this->em->getRepository(ChampsFormulaire::class)->find($key);
            $this->fields = sprintf('%s%s%s',
                $this->fields,
                $champsformulaire->getLibelle(),
                ','
            );
        }
    }

    /**
     * get Line Data
     *
     * @return void
     */
    public function getLineData(): void
    {
        foreach ($this->enregistrement as $enregistrement) {
            foreach ($enregistrement->getResultats() as $result) {
                if (is_array($result)) {
                    $result = $result["date"];
                }
            
                $this->lineData = sprintf('%s%s%s',
                    $this->lineData,
                    $result,
                    ','
                );
            }
            $this->lineData .="\n";
        }
    }

    /**
     * File Format
     *
     * @return string
     */
    public function getNameFile(): string
    {
        return sprintf('resultats_formulaire_%s_%s.%s',
            $this->formulaire->getLibelle(),
            date('Y-m-d'),
            $this->format
        );
    }
}

