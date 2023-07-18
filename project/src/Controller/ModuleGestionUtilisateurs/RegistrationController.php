<?php

namespace App\Controller\ModuleGestionUtilisateurs;

use App\Entity\User;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\Intervenants\RegistrationFormType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends BaseController
{
    /**
     * @Route("/intervenant/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $menus = $this->serviceMenu();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
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
     * @Route("/intervenant/intervenants", name="intervenants")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function intervenants(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $menus = $this->serviceMenu();
        $user = new User();

        $intervenants = $this->em->getRepository(User::class)->findBy([]);

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
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

        return $this->render('intervenants/index.html.twig', [
            'menus' => $menus,
            'registrationForm' => $form->createView(),
            'intervenants' => $intervenants,
        ]);
    }

    /**
     * @Route("/intervenant/delete/{id}", name="delete_intervenant")
     */
    public function delete_intervenant($id): Response
    {
        if(!$id) {
            throw $this->createNotFoundException('No ID found');
        }

        $user = $this->em->getRepository(User::class)->find($id);

        if($user != null) {
            $this->em->remove($user);
            $this->em->flush();
        }

        return $this->redirectToRoute('intervenants');
    }

}
