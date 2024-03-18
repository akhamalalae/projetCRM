<?php

namespace App\Core\Service\EnregistrementFormulaire;

use App\Core\Interface\DeleteObjectInterface;
use App\Core\Interface\InitialisationInterface;
use Doctrine\ORM\EntityManagerInterface;

class DeleteImage implements InitialisationInterface, DeleteObjectInterface
{
    private $image;
    private $imagesDirectory;

    public function __construct(public EntityManagerInterface $em)
    {
    }

    //InitialisationInterface

    /**
     * Initialisation
     *
     * @param array $params
     * @return void
     */
    public function init($param)
    {
        $this->image           = $param['image'];
        $this->imagesDirectory = $param['imagesDirectory'];
    }

    //DeleteObjectInterface

    /**
     * delete data
     *
     * @param object $object
     * @return void
     */
    public function delete()
    {
        $this->deleteSpecific();

        $nom = $this->image->getFile();
        unlink($this->imagesDirectory.'/'.$nom);

        $this->em->remove($this->image);
        $this->em->flush();
    }

    /**
     * delete specific data
     *
     * @param object $object
     * @return void
     */
    public function deleteSpecific()
    {
    }
}

