<?php

namespace App\Controller\ModuleGestionOutilsPilotage;

use App\Core\Service\GestionOutilsPilotage\GenerationAutomatiqueCalender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Core\Trait\RenderTrait;

class GenerationAutomatiqueRendezVous extends AbstractController
{
    use RenderTrait;

    /**
     * @Route("/gestionnaire/generation/automatique/rendez/vous", name="generation_automatique_rendez_vous", methods={"GET","POST"})
     *
     * @param Request $request
     * @param GenerationAutomatiqueCalender $service
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function generationAutomatiqueRendezVous(Request $request, GenerationAutomatiqueCalender $service):Response
    {
        ini_set('memory_limit','2048M');
        set_time_limit(600);
        return $this->renderTrait($request, $service,['user' => $this->getUser()]);
    }
}
