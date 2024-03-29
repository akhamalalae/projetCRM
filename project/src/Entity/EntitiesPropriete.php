<?php

namespace App\Entity;

use App\Repository\EntitiesProprieteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EntitiesProprieteRepository::class)
 */
class EntitiesPropriete
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
     * @ORM\ManyToOne(targetEntity=Entities::class, inversedBy="entitiesProprietes")
     */
    private $entitie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $methode;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Typeschamps::class, inversedBy="entitiesProprietes")
     */
    private $typesChamps;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=RequeteTableauBordFiltres::class, mappedBy="entities_propriete")
     */
    private $requeteTableauBordFiltres;

    /**
     * @ORM\ManyToMany(targetEntity=RequeteTableauBord::class, mappedBy="properties_entity_choix_champs")
     */
    private $requeteTableauBordChoixChamps;

    /**
     * @ORM\ManyToOne(targetEntity=Entities::class, inversedBy="entitiesProprietesJointures")
     */
    private $entitie_joiture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fonctionAgregation;

    public function __construct()
    {
        $this->requeteTableauBordFiltres = new ArrayCollection();
        $this->requeteTableauBordChoixChamps = new ArrayCollection();
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

    public function getEntitie(): ?Entities
    {
        return $this->entitie;
    }

    public function setEntitie(?Entities $entitie): self
    {
        $this->entitie = $entitie;

        return $this;
    }

    public function getMethode(): ?string
    {
        return $this->methode;
    }

    public function setMethode(?string $methode): self
    {
        $this->methode = $methode;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTypesChamps(): ?Typeschamps
    {
        return $this->typesChamps;
    }

    public function setTypesChamps(?Typeschamps $typesChamps): self
    {
        $this->typesChamps = $typesChamps;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNameTypesChamps(): ?string
    {

        return $this->name."( ".$this->typesChamps->getLibelle()." )";
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
            $requeteTableauBordFiltre->setEntitiesPropriete($this);
        }

        return $this;
    }

    public function removeRequeteTableauBordFiltre(RequeteTableauBordFiltres $requeteTableauBordFiltre): self
    {
        if ($this->requeteTableauBordFiltres->removeElement($requeteTableauBordFiltre)) {
            // set the owning side to null (unless already changed)
            if ($requeteTableauBordFiltre->getEntitiesPropriete() === $this) {
                $requeteTableauBordFiltre->setEntitiesPropriete(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RequeteTableauBord>
     */
    public function getRequeteTableauBordChoixChamps(): Collection
    {
        return $this->requeteTableauBordChoixChamps;
    }

    public function addRequeteTableauBordChoixChamp(RequeteTableauBord $requeteTableauBordChoixChamp): self
    {
        if (!$this->requeteTableauBordChoixChamps->contains($requeteTableauBordChoixChamp)) {
            $this->requeteTableauBordChoixChamps[] = $requeteTableauBordChoixChamp;
            $requeteTableauBordChoixChamp->addPropertiesEntityChoixChamp($this);
        }

        return $this;
    }

    public function removeRequeteTableauBordChoixChamp(RequeteTableauBord $requeteTableauBordChoixChamp): self
    {
        if ($this->requeteTableauBordChoixChamps->removeElement($requeteTableauBordChoixChamp)) {
            $requeteTableauBordChoixChamp->removePropertiesEntityChoixChamp($this);
        }

        return $this;
    }

    public function getEntitieJoiture(): ?Entities
    {
        return $this->entitie_joiture;
    }

    public function setEntitieJoiture(?Entities $entitie_joiture): self
    {
        $this->entitie_joiture = $entitie_joiture;

        return $this;
    }

    public function getFonctionAgregation(): ?string
    {
        return $this->fonctionAgregation;
    }

    public function setFonctionAgregation(?string $fonctionAgregation): self
    {
        $this->fonctionAgregation = $fonctionAgregation;

        return $this;
    }

    public function createNameRequeteClauseSelect(): string
    {
        return sprintf("%s (%s)", $this->getName(), $this->getEntitie()?->getLibelle());
    }

    public function jointureName(): string
    {
        return sprintf("%s.%s", lcfirst($this->getEntitie()->getLibelle()), $this->getLibelle());
    }

    public function createRequeteJoint(): string
    {
        return sprintf(
            "  LEFT JOIN %s.%s %s",
            lcfirst($this->getEntitieJoiture()?->getLibelle()),
            $this->getEntitie()?->getNomProprieteeJointure(),
            lcfirst($this->getEntitie()->getLibelle())
        );
    }

    public function createRequeteClauseSelect(): string
    {
        $fonctionAgregation = $this->getFonctionAgregation();

        return sprintf(
            "%s(%s.%s) AS %s",
            ($fonctionAgregation) !== null ? $fonctionAgregation : '',
            lcfirst($this->getEntitie()?->getLibelle()),
            $this->getLibelle(),
            $this->clauseSelectName()
        );
    }

    public function createRequeteClauseGroupeBy(): string
    {
        return sprintf("%s", $this->clauseSelectName());
    }

    public function clauseSelectName(): string
    {
        return sprintf("%s_%s", $this->getLibelle(), $this->id);
    }
}
