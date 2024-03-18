<?php

namespace App\Core\Service\Calendar;

use App\Core\Interface\DeleteObjectInterface;
use App\Core\Interface\InitialisationInterface;
use App\Core\Interface\RedirectToRouteInterface;
use App\Entity\RenderVous;
use Doctrine\ORM\EntityManagerInterface;

class DeleteCalender implements InitialisationInterface, DeleteObjectInterface, RedirectToRouteInterface
{
    private int    $id;
    private object $calendar;

    const ROUTE    = 'calendar_vue_agenda';

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
        $this->calendar = $this->em->getRepository(RenderVous::class)->find($this->id);
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
        $this->em->remove($this->calendar);
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

