<?php

// src/Controller/BaseController.php
namespace App\Controller\ModuleGestionCalendar;

use App\Entity\RenderVous;
use App\Form\RenderVous\RenderVousType;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Intervenants\AgendaFiltreIntervenantsType;

class CalendarController extends BaseController
{
    /**
     * @Route("/intervenant/calendar", name="calendar_vue_agenda", methods={"GET","POST"})
     */
    public function vueAgenda(Request $request): Response
    {
        $menus = $this->serviceMenu();
        $user = $this->getUser();
        $defaultData = ['message' => 'defaultData'];
        $urlApi = $this->getParameter('urlApi');
        $urlApiGetTokenJWT = $this->getParameter('urlApiGetTokenJWT');

        $events = $this->em->getRepository(RenderVous::class)->findAllRendezVousUtilisateurs($user->getId());
        $countRendezVous = $this->countItems($events);
        $data = $this->rdvs($events);

        $form = $this->createForm(AgendaFiltreIntervenantsType::class, $defaultData);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $events = $this->agendaFiltres($form);
            $countRendezVous = $this->countItems($events);
            $data = $this->rdvs($events);
        }

        return $this->render('calendrierRenderVous/renderVous.html.twig', [
            'menus' => $menus,
            'current_page' => '',
            'countRendezVous' => $countRendezVous,
            'data' => $data,
            'urlApi' => $urlApi,
            "urlApiGetTokenJWT" => $urlApiGetTokenJWT,
            'form' => $form->createView(),
        ]);
    }

    public function agendaFiltres($form)
    {
        $intervenants = array();
        $entreprises = array();
        $formulaire = array();
        $pointeVente = array();

        foreach ($form->getData()["intervenants"] as $key => $value) {
            array_push($intervenants, $value->getId());
        }

        foreach ($form->getData()["entreprises"] as $key => $value) {
            array_push($entreprises, $value->getId());
        }

        foreach ($form->getData()["formulaire"] as $key => $value) {
            array_push($formulaire, $value->getId());
        }

        foreach ($form->getData()["pointeVente"] as $key => $value) {
            array_push($pointeVente, $value->getId());
        }

        $events = $this->em->getRepository(RenderVous::class)->agendaFiltres($intervenants, $entreprises, $formulaire, $pointeVente);

        return $events;
    }

    public function rdvs($events)
    {
        $rdvs = [];
        foreach($events as $event){
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
                'allDay' => $event->getAllDay(),
            ];
        }

        return json_encode($rdvs);
    }

    /**
     * @Route("/intervenant/calendar/rendezVous/effectuer", name="render_vous_effectuer", methods={"GET","POST"})
     */
    public function renderVousEffectuer(Request $request): Response
    {
        $menus = $this->serviceMenu();
        $user = $this->getUser();

        $rendezVous = $this->em->getRepository(RenderVous::class)->findAllRendezVousEffectuerUtilisateurs($user->getId());

        return $this->render('calendrierRenderVous/renderVousEffectuer.html.twig', [
            'menus' => $menus,
            'current_page' => '',
            'rendezVous' => $rendezVous,
        ]);
    }

    /**
     * @Route("/intervenant/calendar/add", name="calendar_add", methods={"GET"})
    */
    public function addRDV(Request $request): Response
    {
        $calendar = new RenderVous();
        $form = $this->createForm(RenderVousType::class, $calendar);
        $form->handleRequest($request);

        return $this->render('calendrierRenderVous/addRendezVousForm.html.twig', [
            'calendar' => $calendar,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/intervenant/calendar/edit", name="calendar_edit", methods={"GET"})
     */
    public function editRDV(Request $request): Response
    {
        $id = $request->get('id');
        $calendar = $this->em->getRepository(RenderVous::class)->findOneById($id);

        $form = $this->createForm(RenderVousType::class, $calendar);
        $form->handleRequest($request);

        return $this->render('calendrierRenderVous/editRendezVousForm.html.twig', [
            'calendar' => $calendar,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/intervenant/calendar/delet", name="calendar_delet", methods={"DELETE"})
     */
    public function delete(Request $request): Response
    {
        $id = $request->get('id');
        $calendar = $this->em->getRepository(RenderVous::class)->findOneById($id);

        $this->em = $this->getDoctrine()->getManager();
        $this->em->remove($calendar);
        $this->em->flush();

        return $this->redirectToRoute('calendar_vue_agenda');
    }

}