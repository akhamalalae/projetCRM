<?php

namespace App\Core\Trait;

trait RenderTrait {

    public function renderTrait($request, $service, $params) {
        if (method_exists($service, 'init')) {
            $service->init($params);
        }

        if (method_exists($service, 'formType') && method_exists($service, 'formData')) {
            $form = $this->createForm($service->formType(),
                $service->formData(),
                $service->formOptions(),
                $service->FormOtherOptions()
            );

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if (method_exists($service, 'save')) {
                    $service->save($form);
                }
    
                if (method_exists($service, 'type') && $service->type() !== '') {
                    $this->addFlash($service->type(), $service->message());
                }
    
                if (method_exists($service, 'route') && $service->route() !== '') {
                    return $this->redirectToRoute(
                        $service->route(),
                        $service->parametersRoute()
                    );
                }
            }
        }

        if (method_exists($service, 'view')) {
            $formCreateView = method_exists($service, 'formName') ? [$service->formName() => $form->createView()] : [];

            return $this->render($service->view(),
                array_merge(
                    $service->parameters(),
                    $formCreateView
                )
            );
        }
    }
}
