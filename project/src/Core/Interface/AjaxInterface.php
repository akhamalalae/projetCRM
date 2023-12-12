<?php

namespace App\Core\Interface;

use Symfony\Component\HttpFoundation\Request;

interface AjaxInterface
{
    /**
     * Get Json
     *
     * @param Request $request
     *
     * @return array
     */
    public function getJson($request):array;
}


