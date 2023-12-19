<?php

namespace App\Controller\ModuleGestionFormulaires;

use App\Controller\BaseController;
use App\Core\Service\ChampsFormulaire\AddChampsFormulaire;
use App\Core\Service\ChampsFormulaire\DeleteChampsFormulaire;
use App\Core\Trait\RenderTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ChampsFormulaireController extends BaseController
{
    use RenderTrait;

    /**
     * @Route("/intervenant/formulaire/champs/{id}/{champ}", name="formulaire_champs")
     *
     * @param Request $request
     * @param AddChampsFormulaire $service
     * @param int $id
     * @param $champ
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listeChampsFormulaire(Request $request, AddChampsFormulaire $service, $id, $champ = null):Response
    {
        return $this->renderTrait($request, $service,['id' => $id, 'champ' => $champ]);
    }

    /**
     * @Route("/intervenant/champ/formulaire/delete/{id}", name="champ_formulaire_delete", methods={"GET","POST"})
     *
     * @param DeleteChampsFormulaire $service
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function delete(DeleteChampsFormulaire $service, $id):RedirectResponse
    {
        if(!$id) {
            throw $this->createNotFoundException('No ID found');
        }

        $service->init(['id' => $id]);

        $service->delete();

        return $this->redirectToRoute($service->route(), $service->parametersRoute());
    }
}

