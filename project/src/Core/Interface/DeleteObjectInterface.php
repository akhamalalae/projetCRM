<?php

namespace App\Core\Interface;

interface DeleteObjectInterface
{
    /**
     * Delete data
     *
     * @param object $object
     * @return void
     */
    public function delete();

    /**
     * Delete specific data
     *
     * @param object $object
     * @return void
     */
    public function deleteSpecific();
}
