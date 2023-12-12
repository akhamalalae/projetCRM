<?php

namespace App\Core\Interface;

use Symfony\Component\Form\Form;

interface SubmittedFormInterface
{
    /**
     * Save form data
     *
     * @param Form $form
     * @return void
     */
    public function save($form);

    /**
     * Save specific data
     *
     * @param Form $form
     * @return void
     */
    public function saveSpecific($form);

    /**
     * Save other data
     *
     * @return void
     */
    public function saveBeforeSubmitFormData();
}
