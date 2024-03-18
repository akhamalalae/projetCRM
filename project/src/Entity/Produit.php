<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\ApiPlatform\ProductsAttachementAction;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
#[ApiResource(
    normalizationContext: ['groups' => ['produit:read']],
    denormalizationContext: ['groups' => ['produit:write']],
    collectionOperations: [
        'get',
        'post',
    ],
    itemOperations: [
        'get',
        'put',
        'patch',
        'delete',
        'post_attachment' => [
            'method' => 'POST',
            'path' => '/produits/{id}/attachement',
            'controller' => ProductsAttachementAction::class,
            'deserialize' => false,
            'openapi_context' => [
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

)]
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['produit:read', 'produit:write'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['produit:read', 'produit:write'])]
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Entreprise::class, inversedBy="produits")
     */
    #[Groups(['produit:read', 'produit:write'])]
    private $entreprises;

    /**
     * @ORM\ManyToMany(targetEntity=CategorieProduits::class, inversedBy="produits")
     */
    #[Groups(['produit:read', 'produit:write'])]
    private $categories;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    #[Groups(['produit:read', 'produit:write'])]
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Groups(['produit:read', 'produit:write'])]
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Groups(['produit:read', 'produit:write'])]
    private $dateModification;

    /**
     * @ORM\OneToMany(targetEntity=Attachement::class, mappedBy="produit")
     */
    #[Groups(['produit:read', 'produit:write'])]
    private $attachement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['produit:read', 'produit:write'])]
    private $libelle;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    #[Groups(['produit:read', 'produit:write'])]
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['produit:read', 'produit:write'])]
    private $description;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->attachement = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

	public function getFields()
                               {
                                   $fields = array();
                           
                                   if (isset($this->id)) {
                                       $fields['id_product'] = intval($this->id);
                                       $fields['name'] = $this->name;
                                   }
                           
                                   return $fields;
                               }

    public function getEntreprises(): ?Entreprise
    {
        return $this->entreprises;
    }

    public function setEntreprises(?Entreprise $entreprises): self
    {
        $this->entreprises = $entreprises;

        return $this;
    }

    /**
     * @return Collection|CategorieProduits[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(CategorieProduits $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(CategorieProduits $category): self
    {
        $this->categories->removeElement($category);

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

    /**
     * @return Collection<int, Attachement>
     */
    public function getAttachement(): Collection
    {
        return $this->attachement;
    }

    public function addAttachement(Attachement $attachement): self
    {
        if (!$this->attachement->contains($attachement)) {
            $this->attachement[] = $attachement;
            $attachement->setProduit($this);
        }

        return $this;
    }

    public function removeAttachement(Attachement $attachement): self
    {
        if ($this->attachement->removeElement($attachement)) {
            // set the owning side to null (unless already changed)
            if ($attachement->getProduit() === $this) {
                $attachement->setProduit(null);
            }
        }

        return $this;
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

}
