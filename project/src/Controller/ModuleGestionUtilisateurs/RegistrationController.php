<?php

namespace App\Controller\ModuleGestionUtilisateurs;

use App\Entity\User;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\Intervenants\RegistrationFormType;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends BaseController
{
    /**
     * @Route("/intervenant/register", name="app_register")
     */
    public function register(Request $request): Response
    {
        $user = new User();
        $menus = $this->serviceMenu();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('intervenants');
        }

        return $this->render('registration/register.html.twig', [
            'menus' => $menus,
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/intervenant/add", name="intervenants")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function intervenants(Request $request)
    {
        $menus = $this->serviceMenu();
        $user = new User();

        $intervenants = $this->em->getRepository(User::class)->findBy([]);

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $groupe = $form->get('groupe')->getData();
            foreach ($groupe as $key => $value) {
                $user->addGroupe($value);
            }

            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('intervenants');
        }

        return $this->render('intervenant/index.html.twig', [
            'menus' => $menus,
            'registrationForm' => $form->createView(),
            'intervenants' => $intervenants,
        ]);
    }

    /**
     * @Route("/intervenant/delete/{id}", name="delete_intervenant")
     */
    public function deleteIntervenant($id): Response
    {
        if(!$id) {
            throw $this->createNotFoundException('No ID found');
        }

        $user = $this->em->getRepository(User::class)->find($id);

        foreach ($user->getIntervenantsFormulaires() as $intervenantsFormulaires) {
            $user->removeIntervenantsFormulaire($intervenantsFormulaires);
        }
        foreach ($user->getRenderVous() as $renderVous) {
            $user->removeRenderVou($renderVous);
        }
        foreach ($user->getRenderVousUserCreateur() as $renderVousUserCreateur) {
            $user->removeRenderVousUserCreateur($renderVousUserCreateur);
        }
        foreach ($user->getHistoriqueGenerationAutomatiqueRoutings() as $histo) {
            $user->removeHistoriqueGenerationAutomatiqueRouting($histo);
        }
        foreach ($user->getFormulaire() as $formulaire) {
            $user->removeFormulaire($formulaire);
        }

        if($user != null) {
            $this->em->remove($user);
            $this->em->flush();
        }

        return $this->redirectToRoute('intervenants');
    }

}
