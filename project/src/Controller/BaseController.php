<?php

// src/Controller/BaseController.php
namespace App\Controller;

use DateTime;
use App\Entity\RenderVous;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{

    public function __construct(public EntityManagerInterface $em)
    {
    }

    /**
     * @Route("/intervenant/home", name="app_home")
     */
    public function index(): Response
    {
        $dateNow = new DateTime();
        $cache = new FilesystemAdapter();
        $baseClient = $cache->getItem('baseClient');
        $manager = $baseClient->get();

        $menuGenerator = new MenuGenerator($this->em);
        $menus = $menuGenerator->getMenu();

        $calendarRepository = $this->em->getRepository(RenderVous::class);
        $rendezVous = $calendarRepository->findRendezVousAujourduit($dateNow);
        $countRendezVous = $calendarRepository->countRendezVous($dateNow);

        return $this->render('accueil/index.html.twig', [
            'rendezVous' => $rendezVous,
            'countRendezVous' => $countRendezVous,
            'menus' => $menus,
        ]);
    }

    public function serviceMenu()
    {
        $menuGenerator = new MenuGenerator($this->em);
        $menus = $menuGenerator->getMenu();
        return $menus;
    }

    public function countItems($items)
    {
        return count($items);
    }

    public function doctrineRemove($entity)
    {
        $this->em->remove($entity);
    }

    public function doctrineFlush()
    {
        $this->em->flush();
    }

    public function doctrinePersist($entity)
    {
        $this->em->persist($entity);
    }

}
