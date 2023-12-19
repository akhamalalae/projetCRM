<?php

namespace App\Core\Interface;

interface DeleteObjectInterface
{
    /**
     * delete data
     *
     * @param object $object
     * @return void
     */
    public function delete();

    /**
     * delete specific data
     *
     * @param object $object
     * @return void
     */
    public function deleteSpecific();
}
