<?php

namespace App\Controller\ModuleGestionUtilisateurs;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Core\Service\Utilisateur\DeleteUtilisateur;
use App\Core\Service\Utilisateur\Intervenants;
use App\Core\Service\Utilisateur\Register;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Core\Trait\RenderTrait;

class RegistrationController extends AbstractController
{
    use RenderTrait;

    /**
     * @Route("/intervenant/register", name="app_register")
     *
     * @param Request $request
     * @param Register $service
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request, Register $service):Response
    {
        return $this->renderTrait($request, $service,[]);
    }

    /**
     * @Route("/intervenant/add", name="intervenants")
     *
     * @param Request $request
     * @param Intervenants $service
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function intervenants(Request $request, Intervenants $service):Response
    {
        return $this->renderTrait($request, $service,[]);
    }

     /**
     * @Route("/intervenant/delete/{id}", name="delete_intervenant")
     *
     * @param DeleteUtilisateur $service
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function delete(DeleteUtilisateur $service, $id):RedirectResponse
    {
        $service->init(['id' => $id]);

        $service->delete();

        return $this->redirectToRoute($service->route(), $service->parametersRoute());
    }
}

