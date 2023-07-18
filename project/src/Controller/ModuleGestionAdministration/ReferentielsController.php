<?php

namespace App\Controller\ModuleGestionAdministration;

use DateTime;
use App\Entity\Referentiels;
use App\Controller\BaseController;
use App\Form\Referentiels\ReferentielsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReferentielsController extends BaseController
{
    /**
     * @Route("/intervenant/referentiels", name="referentiels")
     */
    public function referentiels()
    {
        $menus = $this->serviceMenu();
        $referentiels = $this->em->getRepository(Referentiels::class)->findBy([]);

        return $this->render('referentiels/index.html.twig', [
            'menus' => $menus,
            'referentiels' => $referentiels,
        ]);
    }

    /**
     * @Route("/intervenant/add/referentiels/{id}", name="add_referentiels", methods={"GET","POST"})
     */
    public function add_edite_referentiels(Request $request,$id)
    {
        $menus = $this->serviceMenu();

        if($id != 0) {
            $referentiels = $this->em->getRepository(Referentiels::class)->find($id);
        }else {
            $referentiels = new Referentiels();
        }

        $form = $this->createForm(ReferentielsType::class, $referentiels);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $referentiels->setStatus($form->getData()->getStatus());
            $referentiels->setDescription($form->getData()->getDescription());
            $referentiels->setDateCreation(new DateTime());
            $this->em->persist($referentiels);
            $this->em->flush();

            return $this->redirectToRoute('referentiels');
        }

        return $this->render('referentiels/addReferentiels.html.twig', [
            'menus' => $menus,
            'form' => $form->createView(),
        ]);
    }

}
