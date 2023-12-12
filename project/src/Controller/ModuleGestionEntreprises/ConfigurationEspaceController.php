<?php

namespace App\Controller\ModuleGestionEntreprises;

use App\Controller\BaseController;
use App\Core\Service\ConfigurationEspace\AddConfigurationEspace;
use App\Core\Service\ConfigurationEspace\AddConfigurationEspaceObject;
use App\Core\Service\ConfigurationEspace\ConfigurationEspaceList;
use App\Core\Trait\RenderTrait;
use App\Entity\ConfigurationEspace;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConfigurationEspaceController extends BaseController
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
     */
    public function UpdateConfigurationEspace(Request $request)
    {
        $objCoordonnees = $request->get('objCoordonnees');

        if($objCoordonnees != [] && $objCoordonnees != null){
            foreach ($objCoordonnees as $key => $val) {
                $explodeKey = explode("-", $key);
                $configurationEspace = $this->em->getRepository(ConfigurationEspace::class)->find($explodeKey[1]);
                if ($explodeKey[2] == "x" ) {
                    $configurationEspace->setX($val);
                }elseif($explodeKey[2] == "y"){
                    $configurationEspace->setY($val);
                }

                $this->em->persist($configurationEspace);
                $this->em->flush();
            }
        }
        return $this->json(array('objCoordonnees' => $objCoordonnees));
    }
}

