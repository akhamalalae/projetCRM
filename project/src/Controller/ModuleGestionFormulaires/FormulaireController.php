<?php

namespace App\Controller\ModuleGestionFormulaires;

use App\Entity\Formulaire;
use App\Entity\ChampsFormulaire;
use App\Controller\BaseController;
use App\Entity\EnregistrementFormulaire;
use App\Form\Formulaires\FormulaireType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;

class FormulaireController extends BaseController
{
    /**
     * @Route("/gestionnaire/formulaires", name="formulaire", methods={"GET","POST"})
     */
    public function formulaire()
    {
        $menus = $this->serviceMenu();
        $user = $this->getUser();
        $formulaires = $this->em->getRepository(Formulaire::class)->findBy(['user' => $user,'status' => 0]);
        $countformulaires = $this->countItems($formulaires);

        return $this->render('formulaire/index.html.twig', [
            'menus' => $menus,
            'current_page' => 'formulaire',
            'countformulaires' => $countformulaires,
            'formulaires' => $formulaires
        ]);
    }

    /**
     * @Route("/intervenant/formulaire/search/{chaine}", name="search_formulaire")
     */
    public function search_formulaire($chaine)
    {

        return $this->redirectToRoute('formulaire');
    }

    /**
     * @Route("/intervenant/formulaire/delete/{id}", name="formulaire_delete")
     */
    public function delete($id)
    {
        if(!$id){
            throw $this->createNotFoundException('No ID found');
        }

        $formulaire = $this->em->getRepository(Formulaire::class)->find($id);
        $enregistrementFormulaire = $this->em->getRepository(EnregistrementFormulaire::class)->findBy(['formulaires' => $formulaire]);

        if($formulaire != null){
            if($formulaire->getChampFormulaire()){
                foreach ($formulaire->getChampFormulaire() as $champ) {
                    $champFormulaire = $this->em->getRepository(ChampsFormulaire::class)->find($champ->getId());
                    $this->doctrineRemove($champFormulaire);
                    $this->doctrineFlush();
                }
            }
            if($enregistrementFormulaire){
                foreach ($enregistrementFormulaire as $enregistrement) {
                    $this->doctrineRemove($enregistrement);
                    $this->doctrineFlush();
                }
            }
            $this->doctrineRemove($formulaire);
            $this->doctrineFlush();
        }

        return $this->redirectToRoute('formulaire');
    }

    /**
     * @Route("/intervenant/add/formulaire", name="add_formulaire", methods={"GET","POST"})
     */
    public function add_formulaire(Request $request)
    {
        $menus = $this->serviceMenu();
        $user = $this->getUser();
        $listeFormulaires = $this->em->getRepository(Formulaire::class)->findBy(['user' => $user,'status' => 0]);
        $countformulaires = $this->countItems($listeFormulaires);

        $formulaire = new Formulaire();
        // save the records that are in the database first to compare them with the new one
        // make sure this line comes before the $form->handleRequest();
        $orignalChampsFormulaire = new ArrayCollection();
        foreach ($formulaire->getChampFormulaire() as $champ) {
            $orignalChampsFormulaire->add($champ);
        }
        $form = $this->createForm(FormulaireType::class, $formulaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formulaire->setUser($user);
            $formulaire->setStatus(0);

            foreach ($orignalChampsFormulaire as $champ) {
                if ($formulaire->getChampFormulaire()->contains($champ) === false) {
                    $this->em->remove($champ);
                }
            }
            $this->doctrinePersist($formulaire);
            $this->doctrineFlush();

            return $this->redirectToRoute('formulaire');
        }

        return $this->render('formulaire/addFormulaire.html.twig', [
            'menus' => $menus,
            'current_page' => "add_formulaire",
            'countformulaires' => $countformulaires,
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/intervenant/edit/formulaire/{id}", name="formulaire_edit", methods={"GET","POST"})
     */
    public function edit_formulaire(Request $request,$id)
    {
        $menus = $this->serviceMenu();
        $user = $this->getUser();
        $listeFormulaires = $this->em->getRepository(Formulaire::class)->findBy(['user' => $user,'status' => 0]);
        $countformulaires = $this->countItems($listeFormulaires);
        $formulaire = $this->em->getRepository(Formulaire::class)->find($id);

        // save the records that are in the database first to compare them with the new one
        // make sure this line comes before the $form->handleRequest();
        $orignalChampsFormulaire = new ArrayCollection();
        foreach ($formulaire->getChampFormulaire() as $champ) {
            $orignalChampsFormulaire->add($champ);
        }
        $form = $this->createForm(FormulaireType::class, $formulaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formulaire->setUser($user);
            $formulaire->setStatus($form->getData()->getStatus());

            foreach ($orignalChampsFormulaire as $champ) {
                if ($formulaire->getChampFormulaire()->contains($champ) === false) {
                    $this->em->remove($champ);
                }
            }
            $this->doctrinePersist($formulaire);
            $this->doctrineFlush();

            return $this->redirectToRoute('formulaire');
        }

        return $this->render('formulaire/editFormulaire.html.twig', [
            'menus' => $menus,
            'current_page' => "add_formulaire",
            'champsFormulaire' => $formulaire->getChampFormulaire(),
            'countformulaires' => $countformulaires,
            'form' => $form->createView(),
        ]);
    }

}
