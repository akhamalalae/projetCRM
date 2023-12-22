<?php

namespace App\Core\Interface;

interface SaveDataInterface
{
    /**
     * Save form data
     *
     * @return void
     */
    public function save();

    /**
     * Save specific data
     *
     * @return void
     */
    public function saveSpecific();
}
