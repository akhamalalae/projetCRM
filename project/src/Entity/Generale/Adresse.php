<?php

namespace App\Entity\Generale;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use App\Entity\Ville;

#[MappedSuperclass]
class Adresse
{
    /**
     * @ORM\ManyToOne(targetEntity=Ville::class, inversedBy="pointVentes")
     */
    protected $ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $complementAdresse;


    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getComplementAdresse(): ?string
    {
        return $this->complementAdresse;
    }

    public function setComplementAdresse(?string $complementAdresse): self
    {
        $this->complementAdresse = $complementAdresse;

        return $this;
    }
}
