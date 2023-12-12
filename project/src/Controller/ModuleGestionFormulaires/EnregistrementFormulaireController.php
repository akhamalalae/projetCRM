<?php

namespace App\Controller\ModuleGestionFormulaires;

use DateTime;
use App\Entity\Files;
use App\Entity\Formulaire;
use App\Entity\RenderVous;
use App\Entity\ChampsFormulaire;
use App\Controller\BaseController;
use App\Core\Service\EnregistrementFormulaire\AddDemoEnregistrementFormulaire;
use App\Core\Service\EnregistrementFormulaire\AddEnregistrementFormulaire;
use App\Core\Trait\RenderTrait;
use App\Entity\EnregistrementFormulaire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Form\Formulaires\EnregistrementFormulaireType;

class EnregistrementFormulaireController extends BaseController
{
    use RenderTrait;

     /**
     * @Route("/gestionnaire/demo/formulaire/{id}", name="demo_formulaire", methods={"GET","POST"})
     *
     * @param Request $request
     * @param AddDemoEnregistrementFormulaire $service
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function formulaireDemo(Request $request, AddDemoEnregistrementFormulaire $service, $id):Response
    {
        return $this->renderTrait($request, $service, ['id' => $id]);
    }

    /**
     * @Route("/intervenant/remplir/formulaire/{id}", name="remplir_formulaire", methods={"GET","POST"})
     *
     * @param Request $request
     * @param AddEnregistrementFormulaire $service
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function remplirFormulaire(Request $request, AddEnregistrementFormulaire $service, $id):Response
    {
        return $this->renderTrait($request, $service,
                [
                    'id' => $id,
                    'directory' => $this->getParameter('files_directory'),
                    'user' => $this->getUser()
                ]
        );
    }

    /**
     * @Route("/gestionnaire/resultats/formulaire/{id}", name="resultats_formulaire", methods={"GET","POST"})
     */
    public function resultatsFormulaire($id): Response
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

        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])) {
            $nom = $image->getFile();
            unlink($this->getParameter('files_directory').'/'.$nom);

            $this->em->remove($image);
            $this->em->flush();

            return new JsonResponse(['success' => 1]);
        }else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }

    /**
     * @Route("/gestionnaire/resultats/excel/{id}", name="telecharger_excel", methods={"GET","POST"})
     */
    public function telechargerExcel(Request $request,$id)
    {
        $fields = "";
        $lineData = "";

        $enregistrementFormulaire = $this->em->getRepository(EnregistrementFormulaire::class)->findBy(
            ['formulaires' => $id]
        );
        $formulaire = $this->em->getRepository(Formulaire::class)->find($id);

        foreach ($enregistrementFormulaire[0]->getResultats() as $key => $value) {
            $champsformulaire = $this->em->getRepository(ChampsFormulaire::class)->find($key);
            $fields = $fields.$champsformulaire->getLibelle().",";
        }

        foreach ($enregistrementFormulaire as $key => $value) {
            foreach ($value->getResultats() as $valueEnregistrementFormulaire) {
                if(is_array($valueEnregistrementFormulaire)){
                    $valueEnregistrementFormulaire = $valueEnregistrementFormulaire["date"];
                }
                $lineData = $lineData.$valueEnregistrementFormulaire.",";
            }
            $lineData .="\n";
        }

        $res = $fields."\n".$lineData;

        $fileNameExel = "resultats_formulaire_".$formulaire->getLibelle()."_".date('Y-m-d') . ".xls";
        $fileNameExel = "resultats_formulaire_".$formulaire->getLibelle()."_".date('Y-m-d') . ".csv";

        return new Response(
            $res,
            200,
            ['Content-Type' => 'application/vnd.ms-excel', "Content-disposition" => "attachment; filename=$fileNameExel"]
        );
    }
}
