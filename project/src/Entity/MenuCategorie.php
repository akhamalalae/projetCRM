<?php

namespace App\Entity;

use App\Repository\MenuCategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MenuCategorieRepository::class)
 */
class MenuCategorie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=MenuSousCategorie::class, mappedBy="menuSousCategorie")
     */
    private $menuSousCategorie;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ordre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $icone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $statut;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_creation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_modification;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $access;

    public function __construct()
    {
        $this->parent = new ArrayCollection();
        $this->menuSousCategorie = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getMenuCategorie(): ?self
    {
        return $this->menuCategorie;
    }

    public function setMenuCategorie(?self $menuCategorie): self
    {
        $this->menuCategorie = $menuCategorie;

        return $this;
    }

    /**
     * @return Collection|MenuSousCategorie[]
     */
    public function getMenuSousCategorie(): Collection
    {
        return $this->menuSousCategorie;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getIcone(): ?string
    {
        return $this->icone;
    }

    public function setIcone(?string $icone): self
    {
        $this->icone = $icone;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(?bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(?\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->date_modification;
    }

    public function setDateModification(?\DateTimeInterface $date_modification): self
    {
        $this->date_modification = $date_modification;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function addMenuSousCategorie(MenuSousCategorie $menuSousCategorie): self
    {
        if (!$this->menuSousCategorie->contains($menuSousCategorie)) {
            $this->menuSousCategorie[] = $menuSousCategorie;
            $menuSousCategorie->setMenuSousCategorie($this);
        }

        return $this;
    }

    public function removeMenuSousCategorie(MenuSousCategorie $menuSousCategorie): self
    {
        if ($this->menuSousCategorie->removeElement($menuSousCategorie)) {
            // set the owning side to null (unless already changed)
            if ($menuSousCategorie->getMenuSousCategorie() === $this) {
                $menuSousCategorie->setMenuSousCategorie(null);
            }
        }

        return $this;
    }

    public function getAccess(): ?string
    {
        return $this->access;
    }

    public function setAccess(?string $access): self
    {
        $this->access = $access;

        return $this;
    }
}
