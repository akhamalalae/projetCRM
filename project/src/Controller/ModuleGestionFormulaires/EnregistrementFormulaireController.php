<?php

namespace App\Controller\ModuleGestionFormulaires;

use DateTime;
use App\Entity\Files;
use App\Entity\Formulaire;
use App\Entity\RenderVous;
use App\Entity\ChampsFormulaire;
use App\Controller\BaseController;
use App\Entity\EnregistrementFormulaire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Form\Formulaires\EnregistrementFormulaireType;

class EnregistrementFormulaireController extends BaseController
{
    /**
     * @Route("/gestionnaire/demo/formulaire/{id}", name="demo_formulaire", methods={"GET","POST"})
     */
    public function formulaire(Request $request,$id): Response
    {
        $menus = $this->serviceMenu();

        $formulaire = $this->em->getRepository(Formulaire::class)->find($id);
        $champsFormulaires = $this->em->getRepository(ChampsFormulaire::class)->findBy(
            ['formulaire' => $id,'status' => 0],
            ['ordre' => 'ASC']
        );

        $form = $this->createForm(EnregistrementFormulaireType::class, $champsFormulaires, [
            'champsFormulaires' => $champsFormulaires,
        ]);
        $form->handleRequest($request);

        return $this->render('enregistrementFormulaire/index.html.twig', [
            'menus' => $menus,
            'formulaire' => $formulaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/intervenant/remplir/formulaire/{id}", name="remplir_formulaire", methods={"GET","POST"})
     */
    public function remplir_formulaire(Request $request,$id): Response
    {
        $menus = $this->serviceMenu();
        $user = $this->getUser();
        $resultats = array();

        $calander_rendez_vous = $this->em->getRepository(RenderVous::class)->find($id);
        $formulaire = $this->em->getRepository(Formulaire::class)->find($calander_rendez_vous->getFormulaire());
        $champsFormulaires = $this->em->getRepository(ChampsFormulaire::class)->findBy(
            ['formulaire' => $formulaire,'status' => 0],
            ['ordre' => 'ASC']
        );

        $enregistrementFormulaire = new EnregistrementFormulaire();

        $form = $this->createForm(EnregistrementFormulaireType::class, $champsFormulaires, [
            'champsFormulaires' => $champsFormulaires,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->getData() as $key => $value) {
                $isFile = '';
                if (str_contains($key, 'files')) {
                    //cas champs files
                    $explodeChampId = explode("_", $key);
                    $key = $explodeChampId[1];
                    $isFile = $explodeChampId[0];
                }
                foreach ($champsFormulaires as $champ) {
                    $champId = $champ->getId();
                    if ($champId == $key) {
                        if ($isFile == 'files') {
                            // On récupère les files transmises
                            $listeFichiers = $form->get($isFile.'_'.$key)->getData();
                            // On boucle sur les files
                            foreach($listeFichiers as $un_fichier) {
                                // On génère un nouveau nom de fichier
                                $fichier = md5(uniqid()).'.'.$un_fichier->guessExtension();

                                // On copie le fichier dans le dossier uploads
                                $un_fichier->move(
                                    $this->getParameter('files_directory'),
                                    $fichier
                                );

                                // On crée la file dans la base de données
                                $file = new Files();
                                $file->setDateCreation(new DateTime());
                                $file->setDateModification(new DateTime());
                                $file->setFile($fichier);
                                $champsFormulaire = $this->em->getRepository(ChampsFormulaire::class)->find($key);
                                $file->setChampsFormulaire($champsFormulaire);
                                $file->setName($un_fichier->getClientOriginalName());
                                $enregistrementFormulaire->addFile($file);
                            }
                            $resultats[$champId] = "files";
                        }else {
                            if (is_object($value) && ($value instanceof DateTime) ) {
                                $resultats[$champId] = $value->format('Y-m-d H:i:s');
                            }else {
                                if($value === true) {
                                    $resultats[$champId] = "OUI";
                                }elseif($value === false) {
                                    $resultats[$champId] = "NON";
                                }else {
                                    $resultats[$champId] = $value;
                                }
                            }
                        }
                    }
                }
            }
            $enregistrementFormulaire->setDateCreation(new DateTime());
            $enregistrementFormulaire->setDateModification(new DateTime());
            $enregistrementFormulaire->setFormulaires($formulaire);
            $enregistrementFormulaire->setIntervenant($user);
            $enregistrementFormulaire->setResultats($resultats);
            $enregistrementFormulaire->setCalanderRendezVous($calander_rendez_vous);
            $calander_rendez_vous->setEffectuer(true);
            $this->em->persist($enregistrementFormulaire);
            $this->em->persist($calander_rendez_vous);
            $this->em->flush();

            return $this->redirectToRoute('calendar_vue_agenda');
        }

        return $this->render('enregistrementFormulaire/index.html.twig', [
            'menus' => $menus,
            'formulaire' => $formulaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/gestionnaire/resultats/formulaire/{id}", name="resultats_formulaire", methods={"GET","POST"})
     */
    public function resultats_formulaire(Request $request,$id): Response
    {
        $menus = $this->serviceMenu();

        $formulaire = $this->em->getRepository(Formulaire::class)->find($id);
        $champsFormulaires = $this->em->getRepository(ChampsFormulaire::class)->findBy(
            ['formulaire' => $id,'status' => 0]
        );
        $enregistrementFormulaire = $this->em->getRepository(EnregistrementFormulaire::class)->findBy(
            ['formulaires' => $id]
        );

        return $this->render('enregistrementFormulaire/formulaireResultats.html.twig', [
            'menus' => $menus,
            'formulaire' => $formulaire,
            'enregistrementFormulaire' => $enregistrementFormulaire,
            'champsFormulaires' => $champsFormulaires
        ]);
    }

    /**
     * @Route("/supprime/image/{id}", name="enregistrement_formulaire_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Files $image, Request $request){

        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])) {
            // On récupère le nom de l'image
            $nom = $image->getFile();
            // On supprime le fichier
            unlink($this->getParameter('files_directory').'/'.$nom);

            // On supprime l'entrée de la base
            $this->em->remove($image);
            $this->em->flush();

            // On répond en json
            return new JsonResponse(['success' => 1]);
        }else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }

    /**
     * @Route("/gestionnaire/resultats/excel/{id}", name="telecharger_excel", methods={"GET","POST"})
     */
    public function telecharger_excel(Request $request,$id)
    {
        $fields = "";
        $lineData = "";

        $enregistrementFormulaire = $this->em->getRepository(EnregistrementFormulaire::class)->findBy(
            ['formulaires' => $id]
        );
        $formulaire = $this->em->getRepository(Formulaire::class)->find($id);

        foreach ($enregistrementFormulaire[0]->getResultats() as $key => $value) {
            $champsformulaire = $this->em->getRepository(ChampsFormulaire::class)->find($key);
            // Column
            $fields = $fields.$champsformulaire->getLibelle().",";
        }

        foreach ($enregistrementFormulaire as $key => $value) {
            foreach ($value->getResultats() as $keyEnregistrementFormulaire  => $valueEnregistrementFormulaire) {
                // Output each row of the data
                if(is_array($valueEnregistrementFormulaire)){
                    $valueEnregistrementFormulaire = $valueEnregistrementFormulaire["date"];
                }
                $lineData = $lineData.$valueEnregistrementFormulaire.",";
            }
            $lineData .="\n";
        }

        $res = $fields."\n".$lineData;

        // Excel file name for download formulaire
        $fileNameExel = "resultats_formulaire_".$formulaire->getLibelle()."_".date('Y-m-d') . ".xls";
        $fileNameExel = "resultats_formulaire_".$formulaire->getLibelle()."_".date('Y-m-d') . ".csv";

        //We give the variable in string to the response, we set the HTTP code to 200
        return new Response(
            $res,
            200,
            [
            //Defines the content of the query as an Excel file
                'Content-Type' => 'application/vnd.ms-excel',
            //We indicate that the file will be in attachment so opening the download box, as well as the definition of the name of the file
                "Content-disposition" => "attachment; filename=$fileNameExel"
            ]
        );
    }

}
