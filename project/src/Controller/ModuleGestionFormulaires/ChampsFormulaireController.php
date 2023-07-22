<?php

namespace App\Controller\ModuleGestionFormulaires;

use DateTime;
use App\Entity\Formulaire;
use App\Entity\ChampsFormulaire;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Formulaires\ChampsFormulaireType;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;

class ChampsFormulaireController extends BaseController
{
    /**
     * @Route("/intervenant/formulaire/champs/{id}/{champ}", name="formulaire_champs")
     */
    public function listeChampsFormulaire(Request $request,$id,$champ=null)
    {
        $menus = $this->serviceMenu();
        $statusOptionsChamps = false;

        $listechampsFormulaires = $this->em->getRepository(ChampsFormulaire::class)->findBy(
            ['formulaire' => $id,'status' => 0]
        );
        $countChampsFormulaires = count($listechampsFormulaires);
        $formulaire = $this->em->getRepository(Formulaire::class)->find($id);

        if($champ != null) {
            $champsFormulaire = $this->em->getRepository(ChampsFormulaire::class)->find($champ);
            $editChamps = true;
            if ($champsFormulaire->getType()->getId() == 5) {
                $statusOptionsChamps = true;
            }else {
                $statusOptionsChamps = false;
            }
        }else {
            $champsFormulaire = new ChampsFormulaire();
            $editChamps = false;
            $statusOptionsChamps = false;
        }

        $orignalOptions = new ArrayCollection();
        foreach ($champsFormulaire->getOptions() as $champ) {
            $orignalOptions->add($champ);
        }

        $form = $this->createForm(ChampsFormulaireType::class, $champsFormulaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $champsFormulaire->setFormulaire($formulaire);
            $champsFormulaire->setStatus(0);
            $champsFormulaire->setDateCreation(new DateTime());
            $champsFormulaire->setDateModification(new DateTime());

            foreach ($orignalOptions as $champ) {
                if ($champsFormulaire->getOptions()->contains($champ) === false) {
                    $this->em->remove($champ);
                }
            }

            if ($editChamps == true) {
                if ($champsFormulaire->getType()->getId() != 5) {
                    foreach ($champsFormulaire->getOptions() as $option) {
                        $this->em->remove($option);
                    }
                }
            }

            $this->em->persist($champsFormulaire);
            $this->em->flush();

            return $this->redirectToRoute('formulaire_champs', array(
                'id' => $id,
                'champ' => null,
            ));
        }

        return $this->render('champsFormulaire/champsFormulaire.html.twig', [
            'menus' => $menus,
            'statusOptionsChamps' => $statusOptionsChamps,
            'editChamps' => $editChamps,
            'formulaire' => $formulaire,
            'countChampsFormulaires' => $countChampsFormulaires,
            'current_page' => 'listeChampsFormulaire',
            'champsFormulaires' => $listechampsFormulaires,
            'form' => $form->createView(),
        ]);
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
