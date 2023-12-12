<?php

namespace App\Core\Trait;

trait RenderTrait {

    public function renderTrait($request, $service, $params) {
        $service->init($params);
        $form = $this->createForm($service->formType(), $service->formData(), $service->formOptions());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->save($form);

            if ($service->type() !== '') {
                $this->addFlash($service->type(), $service->message());
            }

            if ($service->route() !== '') {
                return $this->redirectToRoute($service->route(), $service->parametersRoute());
            }
        }

        return $this->render($service->view(), array_merge($service->parameters(), ['form' => $form->createView()]));
    }
}

