<?php

namespace App\Controller\ModuleGestionFormulaires;

use App\Entity\Formulaire;
use App\Entity\ChampsFormulaire;
use App\Controller\BaseController;
use App\Core\Service\Formulaire\AddEditeFormulaire;
use App\Core\Service\Formulaire\FormulaireList;
use App\Core\Trait\RenderTrait;
use App\Entity\EnregistrementFormulaire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class FormulaireController extends BaseController
{
    use RenderTrait;

     /**
     * @Route("/gestionnaire/formulaires", name="formulaire", methods={"GET","POST"})
     *
     * @param FormulaireList $service
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function formulaire(FormulaireList $service):Response
    {
        $service->init(['user' => $this->getUser()]);
        return $this->render($service->view(), $service->parameters());
    }

    /**
     * @Route("/intervenant/add/formulaire", name="add_formulaire", methods={"GET","POST"})
     *
     * @param Request $request
     * @param AddEditeFormulaire $service
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addFormulaire(Request $request, AddEditeFormulaire $service):Response
    {
        return $this->renderTrait($request, $service,['id' => 0, 'user' => $this->getUser()]);
    }

    /**
     * @Route("/intervenant/edit/formulaire/{id}", name="formulaire_edit", methods={"GET","POST"})
     *
     * @param Request $request
     * @param AddEditeFormulaire $service
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editFormulaire(Request $request, AddEditeFormulaire $service, $id):Response
    {
        return $this->renderTrait($request, $service,['id' => $id, 'user' => $this->getUser()]);
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
        $enregistrementFormulaire = $this->em->getRepository(EnregistrementFormulaire::class)->findBy(
            ['formulaires' => $formulaire]
        );

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
}

