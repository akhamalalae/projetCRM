<?php

namespace App\Core\Interface;

interface CreateFormInterface
{
    /**
     * Set type create form
     *
     * @return string
     */
    public function formType();

    /**
     * Set data create form
     *
     * @return object|null
     */
    public function formData();

    /**
     * Create new object
     *
     * @return object|null
     */
    public function createNewObject();

    /**
     * Set options create form
     *
     * @return array
     */
    public function formOptions();
}
