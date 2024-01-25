<?php

namespace App\Core\Service\EnregistrementFormulaire;

use App\Core\Interface\InitialisationInterface;
use App\Core\Interface\RenderInterface;
use App\Entity\ChampsFormulaire;
use App\Entity\EnregistrementFormulaire;
use App\Entity\Formulaire;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;

class Telecharger implements InitialisationInterface, RenderInterface
{
    private int    $id;
    private object $formulaire;
    private array  $enregistrement =[];
    private array  $champsFormulaires = [];
    private string $fields = '';
    private string $lineData = '';
    private string $format = '';
    private string $contentType = '';

    const VIEW_PATH  = 'generatorPDF/enregistrementFormulaire.html.twig';

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
    public function init($param)
    {
        $this->id             = $param['id'];
        $this->format         = $param['format'];
        $this->formulaire     = $this->em->getRepository(Formulaire::class)->find($this->id);

        if ($this->formulaire) {
            $this->enregistrement = $this->em->getRepository(EnregistrementFormulaire::class)->findBy(
                ['formulaires' => $this->formulaire]
            );

            $this->champsFormulaires = $this->em->getRepository(ChampsFormulaire::class)->findBy(
                ['formulaire' => $this->formulaire, 'status' => 0]
            );
        }

        if ($this->format === 'pdf') {
            $this->contentType = 'application/pdf';
        }

        if ($this->format === 'xls') {
            $this->contentType = 'application/vnd.ms-excel';
        }
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
            'Content-Type'        => $this->contentType,
            "Content-disposition" => "attachment; filename=".$this->getNameFile()
        ];
    }

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
            'enregistrementFormulaire' => $this->enregistrement,
            'champsFormulaires'        => $this->champsFormulaires
        ];
    }

    /**
     * generate exel
     *
     * @return void
     */
    public function xlsGenerator(): string
    {
        $this->getFields();
        $this->getLineData();

        return sprintf('%s \n %s',$this->fields, $this->lineData);
    }

    /**
     * generate pdf
     *
     * @param string $html
     * 
     * @return Dompdf
     */
    public function pdfGenerator(string $html): Dompdf
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();

        return $dompdf;
    }

     /**
     * stream file name
     *
     * @return string
     */
    public function streamFilename(): string
    {
        return $this->getNameFile();
    }

     /**
     * stream oprtions
     *
     * @return array
     */
    public function streamOptions(): array
    {
        return [
            "Attachment" => false
        ];
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
            $this->fields = sprintf('%s%s,',$this->fields, $champsformulaire->getLibelle());
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
            
                $this->lineData = sprintf('%s%s,', $this->lineData, $result);
            }
            $this->lineData .= "\n";
        }
    }

    /**
     * File Format
     *
     * @return string
     */
    public function getNameFile(): string
    {
        return sprintf('resultats_formulaire_%s_%s.%s',$this->formulaire->getLibelle(), date('Y-m-d'), $this->format);
    }

    /**
     * image To Base 64
     *
     * @param string $path
     * 
     * @return string
     */
    private function imageToBase64(string $path): string 
    {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        return $base64;
    }
}

