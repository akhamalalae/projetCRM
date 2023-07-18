<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
#[ApiResource(
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']],
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['user:read', 'user:write', 'formulaire'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    #[Groups(['user:read', 'user:write', 'formulaire'])]
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    #[Groups(['user:read', 'user:write', 'formulaire'])]
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    #[Groups(['user:read', 'user:write', 'formulaire'])]
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['user:read', 'user:write', 'formulaire'])]
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['user:read', 'user:write', 'formulaire'])]
    private $firstname;

    /**
     * @ORM\OneToMany(targetEntity=Formulaire::class, mappedBy="user", orphanRemoval=true)
     */
    #[Groups(['user:read', 'user:write', 'formulaire'])]
    private $formulaire;

    /**
     * @ORM\ManyToMany(targetEntity=Formulaire::class, mappedBy="intervenants")
     */
    #[Groups(['user:read', 'user:write', 'formulaire'])]
    private $intervenantsFormulaires;

    /**
     * @ORM\OneToMany(targetEntity=RenderVous::class, mappedBy="intervenant")
     */
    #[Groups(['user:read', 'user:write', 'formulaire'])]
    private $renderVous;

    /**
     * @ORM\OneToMany(targetEntity=RenderVous::class, mappedBy="userCreateur")
     */
    #[Groups(['user:read', 'user:write', 'formulaire'])]
    private $renderVousUserCreateur;

    /**
     * @ORM\OneToMany(targetEntity=HistoriqueGenerationAutomatiqueRouting::class, mappedBy="userCreateur")
     */
    #[Groups(['user:read', 'user:write', 'formulaire'])]
    private $historiqueGenerationAutomatiqueRoutings;

    /**
     * @ORM\ManyToMany(targetEntity=GroupUsers::class, inversedBy="users")
     */
    #[Groups(['user:read', 'user:write', 'formulaire'])]
    private $groupe;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModification;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $complementAdresse;

    /**
     * @ORM\ManyToOne(targetEntity=Ville::class, inversedBy="users")
     */
    private $ville;

    public function __construct()
    {
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        $this->roles = $roles;
        //return array_unique($roles);
        $this->formulaire = new ArrayCollection();
        $this->intervenantsFormulaires = new ArrayCollection();
        $this->renderVous = new ArrayCollection();
        $this->renderVousUserCreateur = new ArrayCollection();
        $this->historiqueGenerationAutomatiqueRoutings = new ArrayCollection();
        $this->groupe = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return Collection|Formulaire[]
     */
    public function getFormulaire(): Collection
    {
        return $this->formulaire;
    }

    public function addFormulaire(Formulaire $formulaire): self
    {
        if (!$this->formulaire->contains($formulaire)) {
            $this->formulaire[] = $formulaire;
            $formulaire->setUser($this);
        }

        return $this;
    }

    public function removeFormulaire(Formulaire $formulaire): self
    {
        if ($this->formulaire->removeElement($formulaire)) {
            // set the owning side to null (unless already changed)
            if ($formulaire->getUser() === $this) {
                $formulaire->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Formulaire[]
     */
    public function getIntervenantsFormulaires(): Collection
    {
        return $this->intervenantsFormulaires;
    }

    public function addIntervenantsFormulaire(Formulaire $intervenantsFormulaire): self
    {
        if (!$this->intervenantsFormulaires->contains($intervenantsFormulaire)) {
            $this->intervenantsFormulaires[] = $intervenantsFormulaire;
            $intervenantsFormulaire->addIntervenant($this);
        }

        return $this;
    }

    public function removeIntervenantsFormulaire(Formulaire $intervenantsFormulaire): self
    {
        if ($this->intervenantsFormulaires->removeElement($intervenantsFormulaire)) {
            $intervenantsFormulaire->removeIntervenant($this);
        }

        return $this;
    }

    /**
     * @return Collection|RenderVous[]
     */
    public function getRenderVous(): Collection
    {
        return $this->renderVous;
    }

    public function addRenderVou(RenderVous $renderVou): self
    {
        if (!$this->renderVous->contains($renderVou)) {
            $this->renderVous[] = $renderVou;
            $renderVou->setIntervenant($this);
        }

        return $this;
    }

    public function removeRenderVou(RenderVous $renderVou): self
    {
        if ($this->renderVous->removeElement($renderVou)) {
            // set the owning side to null (unless already changed)
            if ($renderVou->getIntervenant() === $this) {
                $renderVou->setIntervenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RenderVous[]
     */
    public function getRenderVousUserCreateur(): Collection
    {
        return $this->renderVousUserCreateur;
    }

    public function addRenderVousUserCreateur(RenderVous $renderVousUserCreateur): self
    {
        if (!$this->renderVousUserCreateur->contains($renderVousUserCreateur)) {
            $this->renderVousUserCreateur[] = $renderVousUserCreateur;
            $renderVousUserCreateur->setUserCreateur($this);
        }

        return $this;
    }

    public function removeRenderVousUserCreateur(RenderVous $renderVousUserCreateur): self
    {
        if ($this->renderVousUserCreateur->removeElement($renderVousUserCreateur)) {
            // set the owning side to null (unless already changed)
            if ($renderVousUserCreateur->getUserCreateur() === $this) {
                $renderVousUserCreateur->setUserCreateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|HistoriqueGenerationAutomatiqueRouting[]
     */
    public function getHistoriqueGenerationAutomatiqueRoutings(): Collection
    {
        return $this->historiqueGenerationAutomatiqueRoutings;
    }

    public function addHistoriqueGenerationAutomatiqueRouting(HistoriqueGenerationAutomatiqueRouting $historiqueGenerationAutomatiqueRouting): self
    {
        if (!$this->historiqueGenerationAutomatiqueRoutings->contains($historiqueGenerationAutomatiqueRouting)) {
            $this->historiqueGenerationAutomatiqueRoutings[] = $historiqueGenerationAutomatiqueRouting;
            $historiqueGenerationAutomatiqueRouting->setUserCreateur($this);
        }

        return $this;
    }

    public function removeHistoriqueGenerationAutomatiqueRouting(HistoriqueGenerationAutomatiqueRouting $historiqueGenerationAutomatiqueRouting): self
    {
        if ($this->historiqueGenerationAutomatiqueRoutings->removeElement($historiqueGenerationAutomatiqueRouting)) {
            // set the owning side to null (unless already changed)
            if ($historiqueGenerationAutomatiqueRouting->getUserCreateur() === $this) {
                $historiqueGenerationAutomatiqueRouting->setUserCreateur(null);
            }
        }

        return $this;
    }

    public function getGroupeString(): String
    {
        $chaine = '';
        foreach ($this->groupe as $key => $value) {
            $chaine = $chaine.$value->getLibelle().', ';
        }
        return $chaine;
    }

    /**
     * @return Collection|GroupUsers[]
     */
    public function getGroupe(): Collection
    {
        return $this->groupe;
    }

    public function addGroupe(GroupUsers $groupe): self
    {
        if (!$this->groupe->contains($groupe)) {
            $this->groupe[] = $groupe;
        }

        return $this;
    }

    public function removeGroupe(GroupUsers $groupe): self
    {
        $this->groupe->removeElement($groupe);

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

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }
}