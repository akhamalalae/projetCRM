<?php

namespace App\Core\Interface;

interface RenderInterface
{
    /**
     * view
     *
     * @return string
     */
    public function view();

    /**
     * parameters
     *
     * @return array
     */
    public function parameters();
}

