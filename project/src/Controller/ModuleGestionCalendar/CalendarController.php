<?php

// src/Controller/BaseController.php
namespace App\Controller\ModuleGestionCalendar;

use App\Core\Service\Calendar\AddEditeCalendar;
use App\Core\Service\Calendar\Calendar;
use App\Core\Service\Calendar\CalendarList;
use App\Core\Service\Calendar\DeleteCalender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Core\Trait\RenderTrait;

class CalendarController extends AbstractController
{
    use RenderTrait;

     /**
     * @Route("/intervenant/calendar", name="calendar_vue_agenda", methods={"GET","POST"})
     *
     * @param Request $request
     * @param Calendar $service
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function vueAgenda(Request $request, Calendar $service):Response
    {
        return $this->renderTrait($request, $service,
            [
                'user'              => $this->getUser(),
                'urlApi'            => $this->getParameter('urlApi'),
                'urlApiGetTokenJWT' => $this->getParameter('urlApiGetTokenJWT')
            ]
        );
    }

     /**
     * @Route("/intervenant/calendar/rendezVous/effectuer", name="render_vous_effectuer", methods={"GET","POST"})
     *
     * @param CalendarList $service
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderVousEffectuer(CalendarList $service):Response
    {
        $service->init(['user' => $this->getUser()]);

        return $this->render($service->view(), $service->parameters());
    }

     /**
     * @Route("/intervenant/calendar/add", name="calendar_add", methods={"GET"})
     *
     * @param Request $request
     * @param AddEditeCalendar $service
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addRDV(Request $request, AddEditeCalendar $service):Response
    {
        return $this->renderTrait($request, $service, ['id' => 0, 'user' => $this->getUser()]);
    }

     /**
     * @Route("/intervenant/calendar/edit", name="calendar_edit", methods={"GET"})
     *
     * @param Request $request
     * @param AddEditeCalendar $service
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editRDV(Request $request, AddEditeCalendar $service):Response
    {
        return $this->renderTrait($request, $service, ['id' => $request->get('id'), 'user' => $this->getUser()]);
    }

     /**
     * @Route("/intervenant/calendar/delet", name="calendar_delet", methods={"DELETE"})
     *
     * @param Request $request
     * @param DeleteCalender $service
     *
     * @return RedirectResponse
     */
    public function delete(Request $request, DeleteCalender $service):RedirectResponse
    {
        $service->init(['id' => $request->get('id')]);

        $service->delete();

        return $this->redirectToRoute($service->route(), $service->parametersRoute());
    }
}
