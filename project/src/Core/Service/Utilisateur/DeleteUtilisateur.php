<?php

namespace App\Core\Service\Utilisateur;

use App\Core\Interface\DeleteObjectInterface;
use App\Core\Interface\InitialisationInterface;
use App\Core\Interface\RedirectToRouteInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class DeleteUtilisateur implements InitialisationInterface, DeleteObjectInterface, RedirectToRouteInterface
{
    private int    $id;
    private object $user;

    const ROUTE    = 'intervenants';

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
        $this->user = $this->em->getRepository(User::class)->find($this->id);
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

        $this->em->remove($this->user);
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
        foreach ($this->user->getIntervenantsFormulaires() as $intervenantsFormulaires) {
            $this->user->removeIntervenantsFormulaire($intervenantsFormulaires);
        }
        foreach ($this->user->getRenderVous() as $renderVous) {
            $this->user->removeRenderVou($renderVous);
        }
        foreach ($this->user->getRenderVousUserCreateur() as $renderVousUserCreateur) {
            $this->user->removeRenderVousUserCreateur($renderVousUserCreateur);
        }
        foreach ($this->user->getHistoriqueGenerationAutomatiqueRoutings() as $histo) {
            $this->user->removeHistoriqueGenerationAutomatiqueRouting($histo);
        }
        foreach ($this->user->getFormulaire() as $formulaire) {
            $this->user->removeFormulaire($formulaire);
        }
    }
}

