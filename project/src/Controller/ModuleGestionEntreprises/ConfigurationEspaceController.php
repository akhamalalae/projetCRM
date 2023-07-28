<?php

namespace App\Controller\ModuleGestionEntreprises;

use App\Entity\PointVente;
use App\Controller\BaseController;
use App\Entity\ConfigurationObjet;
use App\Entity\ConfigurationEspace;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ConfigurationEspace\ConfigurationObjetType;
use App\Form\ConfigurationEspace\ConfigurationEspacePointVenteType;

class ConfigurationEspaceController extends BaseController
{
    /**
     * @Route("/gestionnaires/list/espace", name="list_espace", methods={"GET","POST"})
     */
    public function ListEspace(): Response
    {
        $menus = $this->serviceMenu();

        $pointVente = $this->em->getRepository(PointVente::class)->findAll();

        return $this->render('configurationEspace/index.html.twig', [
            'menus' => $menus,
            'pointVente' => $pointVente,
        ]);
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

    /**
     * @Route("/gestionnaires/configuration/espace/{id}", name="configuration_espace", methods={"GET","POST"})
     */
    public function ConfigurationEspace(Request $request,$id): Response
    {
        $menus = $this->serviceMenu();

        $pointVente = $this->em->getRepository(PointVente::class)->find($id);

        $configurationEspacePointVente = $this->em->getRepository(ConfigurationEspace::class)->findBypointVente($pointVente);

        $configurationEspace = new ConfigurationEspace();

        $form = $this->createForm(ConfigurationEspacePointVenteType::class, $configurationEspace);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $configurationEspace->setPointVente($pointVente);

            $this->em->persist($configurationEspace);
            $this->em->flush();
        }

        return $this->render('configurationEspace/configurationEspace.html.twig', [
            'menus' => $menus,
            'configurationEspacePointVente' => $configurationEspacePointVente,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/gestionnaires/configuration/objet/{id}", name="configuration_objet", methods={"GET","POST"})
     */
    public function ConfigurationObjets(Request $request,$id): Response
    {
        $menus = $this->serviceMenu();

        $configurationEspace = $this->em->getRepository(ConfigurationEspace::class)->find($id);

        $configurationObjets = $this->em->getRepository(ConfigurationObjet::class)->findByConfigurationEspace($configurationEspace);

        $configurationObjet = new ConfigurationObjet();

        $form = $this->createForm(ConfigurationObjetType::class, $configurationObjet);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $configurationObjet->setConfigurationEspace($configurationEspace);

            $this->em->persist($configurationObjet);
            $this->em->flush();
        }

        return $this->render('configurationEspace/configurationObjet.html.twig', [
            'menus' => $menus,
            'configurationEspace' => $configurationEspace,
            'configurationObjets' => $configurationObjets,
            'form' => $form->createView(),
        ]);
    }

}
