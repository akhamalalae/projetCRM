<?php

namespace App\Core\Service\ConfigurationEspace;

use App\Core\Interface\InitialisationInterface;
use App\Core\Interface\SaveDataInterface;
use App\Entity\ConfigurationEspace;
use Doctrine\ORM\EntityManagerInterface;

class UpdateConfigurationEspace implements InitialisationInterface, SaveDataInterface
{
    private array $objCoordonnees;

    public function __construct(public EntityManagerInterface $em)
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
        $this->objCoordonnees = $param['objCoordonnees'];
    }

    //SubmittedFormInterface

    /**
     * Save form data
     *
     * @return void
     */
    public function save()
    {
        foreach ($this->objCoordonnees as $key => $val) {
            $explodeKey = explode("-", $key);
            $configurationEspace = $this->em->getRepository(ConfigurationEspace::class)->find($explodeKey[1]);

            if ($explodeKey[2] == "x" ) {
                $configurationEspace->setX($val);
            }

            if ($explodeKey[2] == "y") {
                $configurationEspace->setY($val);
            }

            $this->em->persist($configurationEspace);
            $this->em->flush();
        }
    }

    /**
     * Save specific data
     *
     * @return void
     */
    public function saveSpecific()
    {
    }
}

