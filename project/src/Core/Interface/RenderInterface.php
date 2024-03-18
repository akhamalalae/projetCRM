<?php

namespace App\Core\Interface;

interface RenderInterface
{
    /**
     * View
     *
     * @return string
     */
    public function view();

    /**
     * Parameters
     *
     * @return array
     */
    public function parameters();
}

