<?php

namespace App\Controller\ModuleGestionFormulaires;

use App\Entity\ChampsFormulaire;
use App\Controller\BaseController;
use App\Core\Service\ChampsFormulaire\AddChampsFormulaire;
use App\Core\Trait\RenderTrait;
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
     */
    public function delete($id)
    {
        if(!$id) {
            throw $this->createNotFoundException('No ID found');
        }

        $champsFormulaire = $this->em->getRepository(ChampsFormulaire::class)->find($id);

        if($champsFormulaire != null) {
            $this->em->remove($champsFormulaire);
            $this->em->flush();
        }

        return $this->redirectToRoute('formulaire_champs', array(
            'id' => $champsFormulaire->getFormulaire()->getId(),
            'champ' => null,
        ));
    }

}
