<?php

namespace App\Controller\ModuleGestionFormulaires;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Core\Service\Formulaire\AddEditeFormulaire;
use App\Core\Service\Formulaire\DeleteFormulaire;
use App\Core\Service\Formulaire\FormulaireList;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Core\Trait\RenderTrait;

class FormulaireController extends AbstractController
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
     *
     * @param DeleteFormulaire $service
     *
     * @return RedirectResponse
     */
    public function delete(DeleteFormulaire $service, $id):RedirectResponse
    {
        $service->init(['id' => $id]);

        $service->delete();

        return $this->redirectToRoute($service->route(), $service->parametersRoute());
    }
}

