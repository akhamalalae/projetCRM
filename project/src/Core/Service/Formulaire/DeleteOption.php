<?php

namespace App\Core\Service\Formulaire;

use App\Core\Interface\DeleteObjectInterface;
use App\Core\Interface\InitialisationInterface;
use App\Core\Interface\RedirectToRouteInterface;
use App\Entity\ChampsFormulaire;
use Doctrine\ORM\EntityManagerInterface;

class DeleteOption implements InitialisationInterface, DeleteObjectInterface, RedirectToRouteInterface
{
    private int    $id;
    private object $champsFormulaire;

    const ROUTE    = 'formulaire_champs';

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
        $this->id = $param['id'];
        $this->champsFormulaire = $this->em->getRepository(ChampsFormulaire::class)->find($this->id);
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
        return [
            'id' => $this->champsFormulaire->getFormulaire()->getId(),
            'champ' => null,
        ];
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
        $this->em->remove($this->champsFormulaire);
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
    }
}

