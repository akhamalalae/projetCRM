<?php

namespace App\Controller\ModuleGestionAdministration;

use App\Core\Service\Referentiels\AddEditeReferentiel;
use App\Core\Service\Referentiels\ReferentielsList;
use App\Core\Trait\RenderTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReferentielsController extends AbstractController
{
    use RenderTrait;

    /**
     * @Route("/intervenant/referentiels", name="referentiels")
     *
     * @param ReferentielsList $service
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function referentiels(ReferentielsList $service):Response
    {
        return $this->render($service->view(), $service->parameters());
    }

    /**
     * @Route("/intervenant/add/referentiels/{id}", name="add_referentiels", methods={"GET","POST"})
     *
     * @param Request $request
     * @param AddEditeReferentiel $service
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addEditeReferentiels(Request $request, AddEditeReferentiel $service, $id):Response
    {
        return $this->renderTrait($request, $service, ['id' => $id]);
    }
}

