<?php

namespace App\Controller\ModuleGestionFormulaires;

use App\Entity\ChampsFormulaire;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OptionsController extends BaseController
{
    /**
     * @Route("/intervenant/option/formulaire/delete/{id}", name="option_delete", methods={"GET","POST"})
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
