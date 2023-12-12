<?php

namespace App\Controller;

use DateTime;
use App\Entity\RenderVous;
use App\Services\MenuGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Services\GenerationAutomatiqueRendezVous;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BaseController extends AbstractController
{
    public function __construct(public EntityManagerInterface $em,
        public UserPasswordEncoderInterface $passwordEncoder,
        public MenuGenerator $menuGenerator,
        public GenerationAutomatiqueRendezVous $generationAutomatiqueRendezVous,
    ){
    }


    /**
     * @Route("/intervenant/testquery/{id}", name="app_testquery")
     */
    public function testquery(Request $request)
    {
        dump($request); die();
    }

    /**
     * @Route("/intervenant/home", name="app_home")
     */
    public function index(): Response
    {
        $dateNow = new DateTime();
        $user = $this->getUser();
        $menus = $this->serviceMenu();

        $calendarRepository = $this->em->getRepository(RenderVous::class);

        $rendezVous = $calendarRepository->getListRendezVousToAchieve($dateNow, $user->getId());

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
