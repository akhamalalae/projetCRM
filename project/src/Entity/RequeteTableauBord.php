<?php

namespace App\Entity;

use App\Repository\RequeteTableauBordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RequeteTableauBordRepository::class)
 */
class RequeteTableauBord
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
     * @ORM\OneToMany(targetEntity=RequeteTableauBordFiltres::class, mappedBy="requete_tableau_bord", cascade={"persist"}, orphanRemoval=true)
     */
    private $requeteTableauBordFiltres;

     //querySqlClauseWhereChoixConditions

    /**
     * @ORM\Column(type="boolean")
     */
    private $enregistrer_requete;

    /**
     * @ORM\ManyToMany(targetEntity=EntitiesPropriete::class, inversedBy="requeteTableauBordChoixChamps")
     */
    private $properties_entity_choix_champs;

    //querySqlClauseSelectChoixChamps

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModification;

    public function __construct()
    {
        $this->requeteTableauBordFiltres = new ArrayCollection();
        $this->properties_entity_choix_champs = new ArrayCollection();
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

    /**
     * @return Collection<int, RequeteTableauBordFiltres>
     */
    public function getRequeteTableauBordFiltres(): Collection
    {
        return $this->requeteTableauBordFiltres;
    }

    public function addRequeteTableauBordFiltre(RequeteTableauBordFiltres $requeteTableauBordFiltre): self
    {
        if (!$this->requeteTableauBordFiltres->contains($requeteTableauBordFiltre)) {
            $this->requeteTableauBordFiltres[] = $requeteTableauBordFiltre;
            $requeteTableauBordFiltre->setRequeteTableauBord($this);
        }

        return $this;
    }

    public function removeRequeteTableauBordFiltre(RequeteTableauBordFiltres $requeteTableauBordFiltre): self
    {
        if ($this->requeteTableauBordFiltres->removeElement($requeteTableauBordFiltre)) {
            // set the owning side to null (unless already changed)
            if ($requeteTableauBordFiltre->getRequeteTableauBord() === $this) {
                $requeteTableauBordFiltre->setRequeteTableauBord(null);
            }
        }

        return $this;
    }

    public function getEnregistrerRequete(): ?bool
    {
        return $this->enregistrer_requete;
    }

    public function setEnregistrerRequete(bool $enregistrer_requete): self
    {
        $this->enregistrer_requete = $enregistrer_requete;

        return $this;
    }

    /**
     * @return Collection<int, EntitiesPropriete>
     */
    public function getPropertiesEntityChoixChamps(): Collection
    {
        return $this->properties_entity_choix_champs;
    }

    public function addPropertiesEntityChoixChamp(EntitiesPropriete $propertiesEntityChoixChamp): self
    {
        if (!$this->properties_entity_choix_champs->contains($propertiesEntityChoixChamp)) {
            $this->properties_entity_choix_champs[] = $propertiesEntityChoixChamp;
        }

        return $this;
    }

    public function removePropertiesEntityChoixChamp(EntitiesPropriete $propertiesEntityChoixChamp): self
    {
        $this->properties_entity_choix_champs->removeElement($propertiesEntityChoixChamp);

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->dateModification;
    }

    public function setDateModification(?\DateTimeInterface $dateModification): self
    {
        $this->dateModification = $dateModification;

        return $this;
    }
}
