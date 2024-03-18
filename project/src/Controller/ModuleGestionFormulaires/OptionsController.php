<?php

namespace App\Controller\ModuleGestionFormulaires;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Core\Service\Formulaire\DeleteOption;
use Symfony\Component\Routing\Annotation\Route;

class OptionsController extends AbstractController
{

     /**
     * @Route("/intervenant/option/formulaire/delete/{id}", name="option_delete", methods={"GET","POST"})
     *
     * @param DeleteOption $service
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function delete(DeleteOption $service, $id):RedirectResponse
    {
        $service->init(['id' => $id]);

        $service->delete();

        return $this->redirectToRoute($service->route(), $service->parametersRoute());
    }
}

