<?php

namespace App\Controller;

use DateTime;
use App\Entity\RenderVous;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BaseController extends AbstractController
{
    public function __construct(public EntityManagerInterface $em,
            public UserPasswordEncoderInterface $passwordEncoder,
            public MenuGenerator $menuGenerator,
    ){
    }

    /**
     * @Route("/intervenant/home", name="app_home")
     */
    public function index(): Response
    {
        $dateNow = new DateTime();

        $menus = $this->serviceMenu();

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
        $menus = $this->menuGenerator->getMenu();

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
