<?php

namespace App\Controller\ModuleGestionEntreprises;

use App\Entity\Produit;
use App\Entity\Entreprise;
use App\Entity\ChampsFormulaire;
use App\Entity\CategorieProduits;
use App\Controller\BaseController;
use App\Entity\EnregistrementFormulaire;
use App\Form\Entreprises\EntrepriseType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\Entreprises\CategorieProduitsType;
use Symfony\Component\Routing\Annotation\Route;

class EntreprisesProduitsController extends BaseController
{
    /**
     * @Route("/gestionnaire/entreprise", name="entreprise", methods={"GET","POST"})
     */
    public function entreprise(): Response
    {
        $menus = $this->serviceMenu();
        $entreprises = $this->em->getRepository(Entreprise::class)->findAll();

        return $this->render('entrepriseProduits/index.html.twig', [
            'menus' => $menus,
            'current_page' => 'entreprise',
            'entreprises' => $entreprises,
        ]);
    }


    /**
     * @Route("/gestionnaire/entreprise/categories/produits", name="categoriesProduits", methods={"GET","POST"})
     */
    public function categoriesProduits(Request $request): Response
    {
        $menus = $this->serviceMenu();

        $listeCategorieProduits = $this->em->getRepository(CategorieProduits::class)->findAll();

        $categorieProduits = new CategorieProduits();

        $form = $this->createForm(CategorieProduitsType::class, $categorieProduits);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($categorieProduits);
            $this->em->flush();

            return $this->redirectToRoute('categoriesProduits');
        }

        return $this->render('entrepriseProduits/categoriesProduits.html.twig', [
            'menus' => $menus,
            'current_page' => 'categoriesProduits',
            'listeCategorieProduits' => $listeCategorieProduits,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/gestionnaire/formulaire/delete/{id}", name="formulaire_delete")
     */
    public function delete($id): Response
    {
        if(!$id) {
            throw $this->createNotFoundException('No ID found');
        }

        $formulaire = $this->em->getRepository(Formulaire::class)->find($id);
        $enregistrementFormulaire = $this->em->getRepository(EnregistrementFormulaire::class)
                                    ->findBy(['formulaires' => $formulaire]);

        if($formulaire != null) {
            if($formulaire->getChampFormulaire()) {
                foreach ($formulaire->getChampFormulaire() as $champ) {
                    $champFormulaire = $this->em->getRepository(ChampsFormulaire::class)->find($champ->getId());
                    $this->em->remove($champFormulaire);
                    $this->em->flush();
                }
            }
            if($enregistrementFormulaire){
                foreach ($enregistrementFormulaire as $enregistrement) {
                    $this->em->remove($enregistrement);
                    $this->em->flush();
                }
            }

            $this->em->remove($formulaire);
            $this->em->flush();
        }

        return $this->redirectToRoute('formulaire');
    }

   /**
     * @Route("/gestionnaire/add/entreprise/produits/{id}", name="entreprise_produits", methods={"GET","POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addEditeEntrepriseProduits(Request $request,$id)
    {
        $menus = $this->serviceMenu();

        if($id != 0) {
            $entreprise = $this->em->getRepository(Entreprise::class)->find($id);
        }else {
            $entreprise = new Entreprise();
        }

        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($entreprise);
            $this->em->flush();

            return $this->redirectToRoute('entreprise');
        }

        return $this->render('entrepriseProduits/entreprisesProduits.html.twig', [
            'menus' => $menus,
            'form' => $form->createView(),
        ]);
    }

    /**
    * @Route("/gestionnaire/produits/formulaires", name="getProduitsFormulairesOptions", methods={"GET","POST"})
    */
    public function getProduitsFormulaires(Request $request)
    {
        $idEntreprises = $request->get('idEntreprises');

        $listeProduits = "";
        if($idEntreprises != []){
            $produits = $this->em->getRepository(Produit::class)->getProduitsFormulaires($idEntreprises);
            foreach ($produits as $produit) {
                $listeProduits .= "<option value=".$produit->getId()." >".$produit->getName()."</option>";
            }
        }

        return $this->json(array('listeProduits' => $idEntreprises));
    }

    /**
     * @Route("/intervenant/point/ventes/calander", name="get_point_ventes_calander", methods={"GET","POST"})
     */
    public function getPointVentesCalander(Request $request)
    {
        $selected = $request->get('selected');
        $listePointeVentes = "";

        if($selected != []) {
            $entreprise = $this->em->getRepository(Entreprise::class)->find($selected);
            $pointeVente = $entreprise->getPointVentes();
            foreach ($pointeVente as $pointeVente) {
                $listePointeVentes .= "<option value=".$pointeVente->getId()." >".$pointeVente->getLibelle()."</option>";
            }
        }

        return $this->json(array('listePointeVentes' => $listePointeVentes));
    }
}
