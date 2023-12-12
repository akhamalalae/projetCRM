<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Generale\Adresse;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EntrepriseRepository::class)
 */
#[ApiResource(
    normalizationContext: ['groups' => ['entreprise:read']],
    denormalizationContext: ['groups' => ['entreprise:write']]
)]
class Entreprise extends Adresse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['entreprise:read', 'entreprise:write'])]
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Groups(['entreprise:read', 'entreprise:write'])]
    private $dateCreationEntreprise;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['entreprise:read', 'entreprise:write'])]
    private $formeJuridique;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['entreprise:read', 'entreprise:write'])]
    private $nomsCommerciaux;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['entreprise:read', 'entreprise:write'])]
    private $numeroSIREN;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['entreprise:read', 'entreprise:write'])]
    private $NumeroSIRET;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['entreprise:read', 'entreprise:write'])]
    private $numerosRCS;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Groups(['entreprise:read', 'entreprise:write'])]
    private $dateImmatriculationRCS;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Groups(['entreprise:read', 'entreprise:write'])]
    private $dateEnregistrementINSEE;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['entreprise:read', 'entreprise:write'])]
    private $capitalSocial;

    /**
     * @ORM\OneToMany(targetEntity=Produit::class, mappedBy="entreprises", cascade={"persist"}, orphanRemoval=true)
     */
    private $produits;

    /**
     * @ORM\ManyToMany(targetEntity=Formulaire::class, mappedBy="entreprises", cascade={"persist"},orphanRemoval=true)
     */
    private $fromFormulaires;

    /**
     * @ORM\ManyToMany(targetEntity=PointVente::class, mappedBy="entreprises", cascade={"persist"}, orphanRemoval=true)
     */
    private $pointVentes;

    /**
     * @ORM\OneToMany(targetEntity=RenderVous::class, mappedBy="entreprise")
     */
    private $rendezVous;

    /**
     * @ORM\OneToMany(targetEntity=RenderVous::class, mappedBy="entreprise")
     */
    private $entrepriseRendezVous;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Groups(['entreprise:read', 'entreprise:write'])]
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Groups(['entreprise:read', 'entreprise:write'])]
    private $dateModification;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->fromFormulaires = new ArrayCollection();
        $this->pointVentes = new ArrayCollection();
        $this->rendezVous = new ArrayCollection();
        $this->entrepriseRendezVous = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreationEntreprise(): ?\DateTimeInterface
    {
        return $this->dateCreationEntreprise;
    }

    public function setDateCreationEntreprise(?\DateTimeInterface $dateCreationEntreprise): self
    {
        $this->dateCreationEntreprise = $dateCreationEntreprise;

        return $this;
    }

    public function getFormeJuridique(): ?string
    {
        return $this->formeJuridique;
    }

    public function setFormeJuridique(?string $formeJuridique): self
    {
        $this->formeJuridique = $formeJuridique;

        return $this;
    }

    public function getNomsCommerciaux(): ?string
    {
        return $this->nomsCommerciaux;
    }

    public function setNomsCommerciaux(?string $nomsCommerciaux): self
    {
        $this->nomsCommerciaux = $nomsCommerciaux;

        return $this;
    }

    public function getNumeroSIREN(): ?string
    {
        return $this->numeroSIREN;
    }

    public function setNumeroSIREN(?string $numeroSIREN): self
    {
        $this->numeroSIREN = $numeroSIREN;

        return $this;
    }

    public function getNumeroSIRET(): ?string
    {
        return $this->NumeroSIRET;
    }

    public function setNumeroSIRET(?string $NumeroSIRET): self
    {
        $this->NumeroSIRET = $NumeroSIRET;

        return $this;
    }

    public function getNumerosRCS(): ?string
    {
        return $this->numerosRCS;
    }

    public function setNumerosRCS(?string $numerosRCS): self
    {
        $this->numerosRCS = $numerosRCS;

        return $this;
    }

    public function getCapitalSocial(): ?string
    {
        return $this->capitalSocial;
    }

    public function setCapitalSocial(?string $capitalSocial): self
    {
        $this->capitalSocial = $capitalSocial;

        return $this;
    }

    /**
     * @return Collection|Produit[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setEntreprises($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getEntreprises() === $this) {
                $produit->setEntreprises(null);
            }
        }

        return $this;
    }

    public function getDateImmatriculationRCS(): ?\DateTimeInterface
    {
        return $this->dateImmatriculationRCS;
    }

    public function setDateImmatriculationRCS(?\DateTimeInterface $dateImmatriculationRCS): self
    {
        $this->dateImmatriculationRCS = $dateImmatriculationRCS;

        return $this;
    }

    public function getDateEnregistrementINSEE(): ?\DateTimeInterface
    {
        return $this->dateEnregistrementINSEE;
    }

    public function setDateEnregistrementINSEE(?\DateTimeInterface $dateEnregistrementINSEE): self
    {
        $this->dateEnregistrementINSEE = $dateEnregistrementINSEE;

        return $this;
    }

    /**
     * @return Collection|Formulaire[]
     */
    public function getFromFormulaires(): Collection
    {
        return $this->fromFormulaires;
    }

    public function addFromFormulaire(Formulaire $fromFormulaire): self
    {
        if (!$this->fromFormulaires->contains($fromFormulaire)) {
            $this->fromFormulaires[] = $fromFormulaire;
            $fromFormulaire->addEntreprise($this);
        }

        return $this;
    }

    public function removeFromFormulaire(Formulaire $fromFormulaire): self
    {
        if ($this->fromFormulaires->removeElement($fromFormulaire)) {
            $fromFormulaire->removeEntreprise($this);
        }

        return $this;
    }

    /**
     * @return Collection|PointVente[]
     */
    public function getPointVentes(): Collection
    {
        return $this->pointVentes;
    }

    public function addPointVente(PointVente $pointVente): self
    {
        if (!$this->pointVentes->contains($pointVente)) {
            $this->pointVentes[] = $pointVente;
            $pointVente->addEntreprise($this);
        }

        return $this;
    }

    public function removePointVente(PointVente $pointVente): self
    {
        if ($this->pointVentes->removeElement($pointVente)) {
            $pointVente->removeEntreprise($this);
        }

        return $this;
    }

    /**
     * @return Collection|RenderVous[]
     */
    public function getRendezVous(): Collection
    {
        return $this->rendezVous;
    }

    public function addRendezVou(RenderVous $rendezVou): self
    {
        if (!$this->rendezVous->contains($rendezVou)) {
            $this->rendezVous[] = $rendezVou;
            $rendezVou->setEntreprise($this);
        }

        return $this;
    }

    public function removeRendezVou(RenderVous $rendezVou): self
    {
        if ($this->rendezVous->removeElement($rendezVou)) {
            // set the owning side to null (unless already changed)
            if ($rendezVou->getEntreprise() === $this) {
                $rendezVou->setEntreprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RenderVous[]
     */
    public function getEntrepriseRendezVous(): Collection
    {
        return $this->entrepriseRendezVous;
    }

    public function addEntrepriseRendezVou(RenderVous $entrepriseRendezVou): self
    {
        if (!$this->entrepriseRendezVous->contains($entrepriseRendezVou)) {
            $this->entrepriseRendezVous[] = $entrepriseRendezVou;
            $entrepriseRendezVou->setEntreprise($this);
        }

        return $this;
    }

    public function removeEntrepriseRendezVou(RenderVous $entrepriseRendezVou): self
    {
        if ($this->entrepriseRendezVous->removeElement($entrepriseRendezVou)) {
            // set the owning side to null (unless already changed)
            if ($entrepriseRendezVou->getEntreprise() === $this) {
                $entrepriseRendezVou->setEntreprise(null);
            }
        }

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
