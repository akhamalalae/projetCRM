<?php

namespace App\Controller\ModuleGestionOutilsPilotage;

use DateTime;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\HistoriqueGenerationAutomatiqueRouting;
use App\Form\Planning\GenerationAutomatiqueRendezVousType;

class GenerationAutomatiqueRendezVous extends BaseController
{
    /**
     * @Route("/gestionnaire/generation/automatique/rendez/vous", name="generation_automatique_rendez_vous", methods={"GET","POST"})
     */
    public function generationAutomatiqueRendezVous(Request $request): Response
    {
        ini_set('memory_limit','2048M');
        set_time_limit(600);
        $menus = $this->serviceMenu();
        $user = $this->getUser();
        $dateNoow = new DateTime();
        $defaultData = ['message' => 'defaultData'];

        $routings = $this->em->getRepository(HistoriqueGenerationAutomatiqueRouting::class)->findBy(['userCreateur' => $user]);

        $form = $this->createForm(GenerationAutomatiqueRendezVousType::class, $defaultData);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dateDebut = $form->getData()["start"];
            $ecart = $form->getData()["nbrMinutes"];
            $formulaires = $form->getData()["formulaires"];
            $dateExecution = $form->getData()["dateExecution"];

            if (intval($dateDebut->format('H')) < 8 || intval($dateDebut->format('H')) > 18) {
                $this->addFlash('warning', "L'heure de la date  de début doit être comprise entre 08h et 18H!");
            }else {
                $this->historiqueGenerationAutomatiqueRouting($dateDebut, $ecart, $formulaires, $dateNoow, $dateExecution);

                return $this->redirectToRoute('generation_automatique_rendez_vous');
            }
        }

        return $this->render('calendrierRenderVous/generationAutomatiqueRendezVous.html.twig', [
            'menus' => $menus,
            'current_page' => 'automatisation_routing',
            'routings' => $routings,
            'form' => $form->createView(),
        ]);
    }

    public function historiqueGenerationAutomatiqueRouting($dateDebut, $ecart, $formulaires, $dateNow, $dateExecution)
    {
        $historiqueGenerationAutomatiqueRouting = new HistoriqueGenerationAutomatiqueRouting();
        $historiqueGenerationAutomatiqueRouting->setDateDebut($dateDebut);
        $historiqueGenerationAutomatiqueRouting->setDateExecution($dateExecution);
        $historiqueGenerationAutomatiqueRouting->setEcartEnMunites($ecart);
        $historiqueGenerationAutomatiqueRouting->setDateCreation($dateNow);
        $historiqueGenerationAutomatiqueRouting->setIsGenerer(false);
        $historiqueGenerationAutomatiqueRouting->setUserCreateur($this->getUser());
        foreach ($formulaires as $formulaire) {
            $historiqueGenerationAutomatiqueRouting->addFormulaire($formulaire);
        }
        $this->doctrinePersist($historiqueGenerationAutomatiqueRouting);
        $this->doctrineFlush();

        return $historiqueGenerationAutomatiqueRouting;
    }
}