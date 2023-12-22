<?php

namespace App\Core\Service\Formulaire;

use App\Core\Interface\DeleteObjectInterface;
use App\Core\Interface\InitialisationInterface;
use App\Core\Interface\RedirectToRouteInterface;
use App\Entity\ChampsFormulaire;
use App\Entity\EnregistrementFormulaire;
use App\Entity\Formulaire;
use Doctrine\ORM\EntityManagerInterface;

class DeleteFormulaire implements InitialisationInterface, DeleteObjectInterface, RedirectToRouteInterface
{
    private int    $id;
    private object $formulaire;
    private object $enregistrementFormulaire;

    const ROUTE    = 'formulaire';

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
        $this->id                       = $param['id'];
        $this->formulaire               = $this->em->getRepository(Formulaire::class)->find($this->id);
        $this->enregistrementFormulaire = $this->em->getRepository(EnregistrementFormulaire::class)->findBy(
            ['formulaires' => $this->formulaire]
        );
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

    //DeleteObjectInterface

    /**
     * delete data
     *
     * @param object $object
     * @return void
     */
    public function delete()
    {
        $this->deleteSpecific();

        $this->em->remove($this->formulaire);
        $this->em->flush();
    }

    /**
     * delete specific data
     *
     * @param object $object
     * @return void
     */
    public function deleteSpecific()
    {
        if ($this->formulaire?->getChampFormulaire()) {
            foreach ($this->formulaire->getChampFormulaire() as $champ) {
                $champFormulaire = $this->em->getRepository(ChampsFormulaire::class)->find($champ->getId());
                $this->em->remove($champFormulaire);
            }
        }

        if ($this->enregistrementFormulaire) {
            foreach ($this->enregistrementFormulaire as $enregistrement) {
                $this->em->remove($enregistrement);
            }
        }
    }
}

