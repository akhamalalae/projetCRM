<?php

namespace App\Core\Interface;

interface AddFlashInterface
{
    /**
     * Type Flash
     *
     * @return string
     */
    public function type();

    /**
     * Message Flash
     *
     * @return string
     */
    public function message();
}


