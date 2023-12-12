<?php

namespace App\Core\Interface;

interface RenderInterface
{
    /**
     * Save form data
     *
     * @return string
     */
    public function view();

    /**
     * Save specific data
     *
     * @return array
     */
    public function parameters();
}

