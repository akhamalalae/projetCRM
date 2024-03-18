<?php

namespace App\Controller\ModuleGestionEntreprises;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Core\Service\ConfigurationEspace\AddConfigurationEspace;
use App\Core\Service\ConfigurationEspace\AddConfigurationEspaceObject;
use App\Core\Service\ConfigurationEspace\ConfigurationEspaceList;
use App\Core\Service\ConfigurationEspace\UpdateConfigurationEspace;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Core\Trait\RenderTrait;

class ConfigurationEspaceController extends AbstractController
{
    use RenderTrait;

     /**
     * @Route("/gestionnaires/list/espace", name="list_espace", methods={"GET","POST"})
     *
     * @param ConfigurationEspaceList $service
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ListEspace(ConfigurationEspaceList $service):Response
    {
        return $this->render($service->view(), $service->parameters());
    }

    /**
     * @Route("/gestionnaires/configuration/espace/{id}", name="configuration_espace", methods={"GET","POST"})
     *
     * @param Request $request
     * @param AddConfigurationEspace $service
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ConfigurationEspace(Request $request, AddConfigurationEspace $service, $id):Response
    {
        return $this->renderTrait($request, $service,['id' => $id]);
    }

    /**
     * @Route("/gestionnaires/configuration/objet/{id}", name="configuration_objet", methods={"GET","POST"})
     *
     * @param Request $request
     * @param AddConfigurationEspaceObject $service
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ConfigurationObjets(Request $request, AddConfigurationEspaceObject $service, $id):Response
    {
        return $this->renderTrait($request, $service,['id' => $id]);
    }

    /**
     * @Route("/gestionnaire/update/espace", name="UpdateConfigurationEspace", methods={"GET","POST"})
     *
     * @param Request $request
     * @param UpdateConfigurationEspace $service
     *
     * @return RedirectResponse
     */
    public function UpdateConfigurationEspace(UpdateConfigurationEspace $service, Request $request):RedirectResponse
    {
        $coordonnees = $request->get('objCoordonnees');

        $service->init(['objCoordonnees' => $coordonnees]);

        $service->save();

        return $this->json(array('objCoordonnees' => $coordonnees));
    }
}

