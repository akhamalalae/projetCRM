<?php

namespace App\Core\Trait;

use Symfony\Component\HttpFoundation\Request;

trait RenderTrait {

     /**
     * Reuse mechanism of certain Symfony methods, reduce complexity, group functionality and avoid reuse of the same code.
     * 
     * @param Request $request
     * @param mixed   $service
     * @param array   $param
     *
     * @return mixed
     */
    public function renderTrait(Request $request, mixed $service, array $params) : mixed
    {
        if (method_exists($service, 'init')) {
            $service->init($params);
        }

        if (method_exists($service, 'formType') && method_exists($service, 'formData')) {
            $form = $this->createForm($service->formType(), $service->formData(), $service->formOptions());

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if (method_exists($service, 'save')) {
                    $service->save($form);
                }
    
                if (method_exists($service, 'type') && $service->type() !== '') {
                    $this->addFlash($service->type(), $service->message());
                }
    
                if (method_exists($service, 'route') && $service->route() !== '') {
                    return $this->redirectToRoute($service->route(), $service->parametersRoute());
                }
            }
        }

        if (method_exists($service, 'view')) {
            return $this->render($service->view(),
                array_merge(
                    $service->parameters(),
                    method_exists($service, 'formName') ? [$service->formName() => $form->createView()] : []
                )
            );
        }
    }
}
