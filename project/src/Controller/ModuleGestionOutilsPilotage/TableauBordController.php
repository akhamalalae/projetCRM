<?php

namespace App\Controller\ModuleGestionOutilsPilotage;

use DateTimeImmutable;
use App\Entity\Entities;
use App\Entity\Formulaire;
use App\Entity\RenderVous;
use App\Entity\EntitiesPropriete;
use App\Controller\BaseController;
use App\Entity\RequeteTableauBord;
use App\Form\TableauBord\TableauBordType;
use App\Entity\TableauBordFiltresOperators;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TableauBord\ChoixTableauBordType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;

class TableauBordController extends BaseController
{
    /**
     * @Route("/gestionnaire/choix/tableau_de_bord", name="choix_tableau_de_bord", methods={"GET","POST"})
     */
    public function choixTableauBord(Request $request)
    {
        $menus = $this->serviceMenu();
        $user = $this->getUser();

        $requeteTableauBord = new RequeteTableauBord();

        $defaultData = ['message' => 'defaultData'];
        $form = $this->createForm(ChoixTableauBordType::class,$defaultData);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $requeteTableauBord = $data["RequeteTableauBord"];
            $choix = $data["choix"];
            if ($choix == 0) {
                //'Créer un nouveau tableau de Bord' => 0,
                return $this->redirectToRoute('tableau_de_bord', array(
                    'id' => 0,
                ));
            }else{
                //'Charger un tableau de Bord' => 1,
                return $this->redirectToRoute('tableau_de_bord', array(
                    'id' => $requeteTableauBord->getId(),
                ));
            }
        }

        return $this->render('tableauBord/choixTableauBord.html.twig', [
            'menus' => $menus,
            'current_page' => 'formulaire',
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/gestionnaire/tableau_de_bord/{id}", name="tableau_de_bord", methods={"GET","POST"})
     */
    public function tableauBrd(Request $request,$id)
    {
        ini_set('memory_limit','2048M');
        set_time_limit(600);
        $menus = $this->serviceMenu();
        $user = $this->getUser();
        $resultatsRequeteTableauBord = null;

        $listes_champs = $this->em->getRepository(EntitiesPropriete::class)->findBy(['status' => 0]);
        $listes_entities = $this->em->getRepository(Entities::class)->findBy(['status' => 0]);

        $orignalRequeteTableauBordFiltres = new ArrayCollection();
        if ($id == 0) {
            //'Créer un nouveau tableau de Bord' => 0,
            $requeteTableauBord = new RequeteTableauBord();
        }else{
            //'Charger un tableau de Bord' => 1,
            $requeteTableauBord = $this->em->getRepository(RequeteTableauBord::class)->find($id);
            // save the records that are in the database first to compare them with the new one
            // make sure this line comes before the $form->handleRequest();
            foreach ($requeteTableauBord->getRequeteTableauBordFiltres() as $champ) {
                $orignalRequeteTableauBordFiltres->add($champ);
            }
        }

        $form = $this->createForm(TableauBordType::class, $requeteTableauBord, [
            'listes_champs' => $listes_champs,
            'em' => $this->em,
        ],$listes_champs);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($orignalRequeteTableauBordFiltres as $champ) {
                if ($requeteTableauBord->getRequeteTableauBordFiltres()->contains($champ) === false) {
                    $this->doctrineRemove($champ);
                }
            }

            $warning = false;
            if (count($requeteTableauBord->getRequeteTableauBordFiltres()) == 0) {
                $this->addFlash('success', 'Veuillez choisir les filtres de la requête!');
                $warning = true;
            }
            if (count($requeteTableauBord->getPropertiesEntityChoixChamps()) == 0) {
                $this->addFlash('success', 'Veuillez choisir les champs a afficher!');
                $warning = true;
            }
            if (($id == 0) && ($requeteTableauBord->getEnregistrerRequete() == true) && $requeteTableauBord->getLibelle() == '') {
                $this->addFlash('success', 'Veuillez remplir le nom de la requête!');
                $warning = true;
            }

            $checkValidateSyntaxeChampValeur = $this->checkValidateSyntaxeChampValeur($requeteTableauBord->getRequeteTableauBordFiltres());
            if ($checkValidateSyntaxeChampValeur == true) {
                $this->addFlash('success', 'Erreur de syntaxe du champ Valeur des filtres, veuillez regarder la documentation!');
                $warning = true;
            }

            if ($warning== false) {
                $this->doctrinePersist($requeteTableauBord);
                $this->doctrineFlush();

                $requeteSQL = $this->createRequete($requeteTableauBord);

                $resultatsRequeteTableauBord = $this->em->getRepository(Formulaire::class)->requeteTableauBordFiltres($requeteSQL);

                $resultatsRequeteTableauBord = $this->changeLabelFormatResultats($resultatsRequeteTableauBord);

                // Ne pas enregistrer la requête dans la base
                if (($id == 0) && ($requeteTableauBord->getEnregistrerRequete() == false)) {
                    $this->doctrineRemove($requeteTableauBord);
                    $this->doctrineFlush();
                }
            }
        }

        return $this->render('tableauBord/index.html.twig', [
            'menus' => $menus,
            'current_page' => 'formulaire',
            'resultatsRequeteTableauBord' => $resultatsRequeteTableauBord,
            'form' => $form->createView(),
            'listes_champs' => $listes_champs,
            'listes_entities' => $listes_entities,
            'requete_tableau_bord_id' =>$id,
            'requete_tableau_bord' =>$requeteTableauBord,
            "nombreFiltres" => count($requeteTableauBord->getRequeteTableauBordFiltres()),
        ]);
    }

    public function changeLabelFormatResultats($resultatsRequeteTableauBord)
    {
        foreach ($resultatsRequeteTableauBord as $key => $var) {
            foreach ($var as $keychamp => $varchamp) {
                $explode = explode("_", $keychamp);
                $champ = $this->em->getRepository(EntitiesPropriete::class)->find($explode[1]);
                $resultatsRequeteTableauBord[$key][$champ->getName()." (".$champ->getEntitie()->getLibelle().")"] = $varchamp;
                unset($resultatsRequeteTableauBord[$key][$keychamp]);
            }
        }

        return $resultatsRequeteTableauBord;
    }

    public function createRequete($requeteTableauBord)
    {
        $clauseWhere = '';
        $clauseSelect = '';
        $jointure = array();

        foreach ($requeteTableauBord->getPropertiesEntityChoixChamps() as $champ) {
            $property = $champ->getLibelle();
            $id = $champ->getId();
            $entity_libelle = $champ->getEntitie()->getLibelle();

            if ($clauseSelect == '') {
            }else{
                $clauseSelect .= " ,";
            }

            $clauseSelect .= lcfirst($entity_libelle) . "." . $property . " AS " . $property . "_" . $id;

            $jointure = $this->createJoint($champ, $entity_libelle, $jointure);
        }

        foreach ($requeteTableauBord->getRequeteTableauBordFiltres() as $filtre) {
            $property = $filtre->getEntitiesPropriete()->getLibelle();
            $operator = $filtre->getTableauBordFiltreOperator()->getLibelle();
            $valeur = $filtre->getValeur();

            if ($operator == "Contient") {
                $operator = "LIKE";
                $valeur = "%$valeur%";
            }

            $condition = "";
            if ($filtre->getTableauBordFiltreCondition() != '') {
                $condition = $filtre->getTableauBordFiltreCondition()->getLibelle();
            }

            $nom_jointure_filtre = lcfirst($filtre->getEntitiesPropriete()->getEntitie()->getLibelle());

            $clauseWhere .= " $condition $nom_jointure_filtre.$property $operator '$valeur' ";

            $champ = $filtre->getEntitiesPropriete();
            $entity_libelle = $filtre->getEntitie()->getLibelle();

            $jointure = $this->createJoint($champ, $entity_libelle, $jointure);
        }

        $clauseJiointures = "";
        foreach ($jointure as $une_jointure) {
            $clauseJiointures .= " $une_jointure";
        }

        $requete = "SELECT $clauseSelect FROM App\Entity\Formulaire formulaire $clauseJiointures WHERE $clauseWhere";

        return $requete;
    }

    public function createJoint($champ, $entity_libelle, $jointure)
    {
        if($champ->getEntitieJoiture() != null&&
            ! array_key_exists($entity_libelle, $jointure)
        ) {
            $property_entity_jointure = $champ->getEntitieJoiture()->getLibelle();
            $entity_nom_jointure = $champ->getEntitie()->getNomProprieteeJointure();
            $jointure[$entity_libelle] = " LEFT JOIN " . lcfirst($property_entity_jointure) . "." . $entity_nom_jointure . " " . lcfirst($entity_libelle);
        }

        return $jointure;
    }

    function checkValidateSyntaxeChampValeur($filtres)
    {
        $erreur = false;

        foreach ($filtres as $filtre) {
            $valeur = $filtre->getValeur();

            if ($filtre->getEntitiesPropriete()->getTypesChamps()->getId() === 9) { //type date
                $format = 'Y/m/d H:i';
                $checkDateFormat = DateTimeImmutable::createFromFormat($format, $valeur);
                if($checkDateFormat === false) {
                    $erreur = true;
                    break;
                }
            }

            if ($filtre->getEntitiesPropriete()->getTypesChamps()->getId() === 11) { //type boolean
                $valeur = $valeur === '0'? 0: 1;
            }
        }

        return $erreur;
    }

     /**
     * @Route("/gestionnaire/tableau_de_bord/get/entities", name="getEntitiesProprieteConditionByEntities", methods={"GET","POST"})
     */
    public function entitiesProprieteConditionByEntities(Request $request)
    {
        $idEntite = $request->get('idEntite');
        $listes_EntitiesPropriete  = "";

        $entitiesProprietes = $this->em->getRepository(EntitiesPropriete::class)->findBy(['entitie' => $idEntite, 'status' => 0]);

        foreach ($entitiesProprietes as $une_prop) {
            $listes_EntitiesPropriete .= "<option value=".$une_prop->getId()." >".$une_prop->getName()."( ".$une_prop->getTypesChamps()->getLibelle()." )"."</option>";
        }

        return $this->json(array('listes_EntitiesPropriete' => $listes_EntitiesPropriete));
    }

     /**
     * @Route("/gestionnaire/tableau_de_bord/get/typeChamp", name="getTypeChamp", methods={"GET","POST"})
     */
    public function typeChamp(Request $request)
    {
        $idEntitePropriete = $request->get('idEntitePropriete');

        $entitiesProprietes = $this->em->getRepository(EntitiesPropriete::class)->find($idEntitePropriete);

        $arrayOperators = [1, 2, 3, 4, 5];

        if($entitiesProprietes->getTypesChamps()->getId() == 9){
            unset($arrayOperators[1]);
        }

        if($entitiesProprietes->getTypesChamps()->getId() == 1 || $entitiesProprietes->getTypesChamps()->getId() == 2){
            unset($arrayOperators[2], $arrayOperators[3]);
        }

        if($entitiesProprietes->getTypesChamps()->getId() == 3 || $entitiesProprietes->getTypesChamps()->getId() == 4){
            unset($arrayOperators[1]);
        }

        if($entitiesProprietes->getTypesChamps()->getId() == 11){
            unset($arrayOperators[1], $arrayOperators[2], $arrayOperators[3], $arrayOperators[4]);
        }

        $operators = $this->em->getRepository(TableauBordFiltresOperators::class)->getOperators($arrayOperators);

        $listOperators = "<option selected>Choisir l'opérateur</option>";
        foreach ($operators as $op) {
            $listOperators .= "<option value=".$op->getId()." >".$op->getLibelle()."</option>";
        }

        return $this->json(array('listOperators' => $listOperators));
    }

    /**
     * @Route("/gestionnaires/synthese", name="synthese_gestionnaires", methods={"GET","POST"})
     */
    public function syntheseGestionnaires(Request $request): Response
    {
        $menus = $this->serviceMenu();

        $rendezVous = $this->em->getRepository(RenderVous::class)->findAll();

        return $this->render('tableauBord/SyntheseGestionnaires.html.twig', [
            'menus' => $menus,
            'rendezVous' => $rendezVous,
        ]);
    }

}
