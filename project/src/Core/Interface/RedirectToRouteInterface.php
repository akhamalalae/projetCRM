<?php

namespace App\Core\Interface;

interface RedirectToRouteInterface
{
    /**
     * Name route
     *
     * @return string
     */
    public function route();

    /**
     * parameters route
     *
     * @return array
     */
    public function parametersRoute();
}


