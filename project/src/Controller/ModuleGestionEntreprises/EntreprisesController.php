<?php

namespace App\Controller\ModuleGestionEntreprises;

use App\Core\Service\Entreprise\AddEditeEntreprise;
use App\Core\Service\Entreprise\Entreprises;
use App\Core\Trait\RenderTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntreprisesController extends AbstractController
{
    use RenderTrait;

    /**
     * @Route("/gestionnaire/entreprise", name="entreprise", methods={"GET","POST"})
     *
     * @param Entreprises $service
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function entreprise(Entreprises $service): Response
    {
        return $this->render($service->view(), $service->parameters());
    }

    /**
     * @Route("/gestionnaire/add/entreprise/produits/{id}", name="entreprise_produits", methods={"GET","POST"})
     *
     * @param Request $request
     * @param AddEditeEntreprise $service
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addEditeEntreprise(Request $request, AddEditeEntreprise $service, $id):Response
    {
        return $this->renderTrait($request, $service, ['id' => $id]);
    }
}
