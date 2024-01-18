<?php

namespace App\Controller\ModuleGestionFormulaires;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Core\Service\EnregistrementFormulaire\AddDemoEnregistrementFormulaire;
use App\Core\Service\EnregistrementFormulaire\AddEnregistrementFormulaire;
use App\Core\Service\EnregistrementFormulaire\DeleteImage;
use App\Core\Service\EnregistrementFormulaire\ResultatsFormulaire;
use App\Core\Service\EnregistrementFormulaire\Telecharger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Files;
use App\Core\Trait\RenderTrait;

class EnregistrementFormulaireController extends AbstractController
{
    use RenderTrait;

     /**
     * @Route("/gestionnaire/demo/formulaire/{id}", name="demo_formulaire", methods={"GET","POST"})
     *
     * @param Request $request
     * @param AddDemoEnregistrementFormulaire $service
     * @param int $id
     *
     * @return Response
     */
    public function formulaireDemo(Request $request, AddDemoEnregistrementFormulaire $service, $id): Response
    {
        return $this->renderTrait($request, $service, ['id' => $id]);
    }

    /**
     * @Route("/intervenant/remplir/formulaire/{id}", name="remplir_formulaire", methods={"GET","POST"})
     *
     * @param Request $request
     * @param AddEnregistrementFormulaire $service
     * @param int $id
     *
     * @return Response
     */
    public function remplirFormulaire(Request $request, AddEnregistrementFormulaire $service, $id): Response
    {
        return $this->renderTrait($request, $service,
                [
                    'id' => $id,
                    'directory' => $this->getParameter('files_directory'),
                    'user' => $this->getUser()
                ]
        );
    }

    /**
     * @Route("/gestionnaire/resultats/formulaire/{id}", name="resultats_formulaire", methods={"GET","POST"})
     *
     * @param ResultatsFormulaire $service
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function resultatsFormulaire(ResultatsFormulaire $service, $id): Response
    {
        $service->init(['id' => $id]);
        return $this->render($service->view(), $service->parameters());
    }

    /**
    * @Route("/supprime/image/{id}", name="enregistrement_formulaire_delete_image", methods={"DELETE"})
    *
    * @param Files $image
    * @param Request $request
    * @param DeleteImage $service
    *
    * @return JsonResponse
    */
    public function deleteImage(Files $image, Request $request, DeleteImage $service): JsonResponse
    {
        $content = json_decode($request->getContent(), true);

        if($this->isCsrfTokenValid('delete'.$image->getId(), $content['_token'])) {
            $service->init(
                [
                    'image'           => $image,
                    'imagesDirectory' => $this->getParameter('files_directory')
                ]
            );

            $service->delete();

            return new JsonResponse(['success' => 1]);
        }

        return new JsonResponse(['error' => 'Token Invalide'], 400);
    }


    /**
     * @Route("/gestionnaire/resultats/telecharger/{id}/{format}", name="telecharger", methods={"GET","POST"})
     *
     * @param Telecharger $service
     * @param int $id
     * @param string $format
     *
     * @return Response
     */
    public function telechargerFile(Telecharger $service, $id, $format = 'xls'): Response
    {
        $service->init(['id' => $id, 'format' => $format]);

        return new Response($service->content(), $service->status(), $service->headers());
    }
}
