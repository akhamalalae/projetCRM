<?php

namespace App\Entity;

use App\Repository\RequeteTableauBordFiltresRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RequeteTableauBordFiltresRepository::class)
 */
class RequeteTableauBordFiltres
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
    private $valeur;

    /**
     * @ORM\ManyToOne(targetEntity=RequeteTableauBord::class, inversedBy="requeteTableauBordFiltres")
     */
    public $requete_tableau_bord;

    /**
     * @ORM\ManyToOne(targetEntity=TableauBordFiltresConditions::class, inversedBy="requeteTableauBordFiltres")
     */
    public $tableau_bord_filtre_condition;

    /**
     * @ORM\ManyToOne(targetEntity=TableauBordFiltresOperators::class, inversedBy="requeteTableauBordFiltres")
     */
    public $tableau_bord_filtre_operator;

    /**
     * @ORM\ManyToOne(targetEntity=EntitiesPropriete::class, inversedBy="requeteTableauBordFiltres")
     */
    public $entities_propriete;

    /**
     * @ORM\ManyToOne(targetEntity=Entities::class, inversedBy="requeteTableauBordFiltres")
     */
    private $entitie;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModification;

    /**
     * @ORM\ManyToOne(targetEntity=Parenthese::class, inversedBy="requeteTableauBordFiltres")
     */
    private $parenthese;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="requeteTableauBordFiltres")
     */
    private $userCreateur;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="requeteTableauBordFiltres")
     */
    private $userModificateur;

    public function __construct()
    {
        $this->dateCreation = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValeur(): ?string
    {
        return $this->valeur;
    }

    public function setValeur(string $valeur = null): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getRequeteTableauBord(): ?RequeteTableauBord
    {
        return $this->requete_tableau_bord;
    }

    public function setRequeteTableauBord(?RequeteTableauBord $requete_tableau_bord): self
    {
        $this->requete_tableau_bord = $requete_tableau_bord;

        return $this;
    }

    public function getTableauBordFiltreCondition(): ?TableauBordFiltresConditions
    {
        return $this->tableau_bord_filtre_condition;
    }

    public function setTableauBordFiltreCondition(?TableauBordFiltresConditions $tableau_bord_filtre_condition): self
    {
        $this->tableau_bord_filtre_condition = $tableau_bord_filtre_condition;

        return $this;
    }

    public function getTableauBordFiltreOperator(): ?TableauBordFiltresOperators
    {
        return $this->tableau_bord_filtre_operator;
    }

    public function setTableauBordFiltreOperator(?TableauBordFiltresOperators $tableau_bord_filtre_operator): self
    {
        $this->tableau_bord_filtre_operator = $tableau_bord_filtre_operator;

        return $this;
    }

    public function getEntitiesPropriete(): ?EntitiesPropriete
    {
        return $this->entities_propriete;
    }

    public function setEntitiesPropriete(?EntitiesPropriete $entities_propriete): self
    {
        $this->entities_propriete = $entities_propriete;

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

    public function getParenthese(): ?Parenthese
    {
        return $this->parenthese;
    }

    public function setParenthese(?Parenthese $parenthese): self
    {
        $this->parenthese = $parenthese;

        return $this;
    }

    public function getUserCreateur(): ?User
    {
        return $this->userCreateur;
    }

    public function setUserCreateur(?User $userCreateur): self
    {
        $this->userCreateur = $userCreateur;

        return $this;
    }

    public function getUserModificateur(): ?User
    {
        return $this->userModificateur;
    }

    public function setUserModificateur(?User $userModificateur): self
    {
        $this->userModificateur = $userModificateur;

        return $this;
    }

    public function getIfParenthesOuvrante(): string
    {
        $parenthese = $this->getParenthese()?->getLibelle();

        if ($parenthese === '(') {
            return $parenthese;
        }

        return '';
    }

    public function getIfParenthesFermante(): string
    {
        $parenthese = $this->getParenthese()?->getLibelle();

        if ($parenthese === ')') {
            return $parenthese;
        }

        return '';
    }

    public function getIfInValidFiltreValue(): string
    {
        $valeur    = $this->getValeur();
        $property  = $this->getEntitiesPropriete()?->getLibelle();
        $entitie   = $this->getEntitie()?->getLibelle();
        $typeChamp = $this->getEntitiesPropriete()?->getTypesChamps()?->getId();
        $operator  = $this->getTableauBordFiltreOperator()?->getId();
        $warning   = false;

        switch ($typeChamp) {
            case Typeschamps::DATETYPE:
                $format = 'Y/m/d H:i';
                $checkDateFormat = DateTimeImmutable::createFromFormat($format, $valeur);

                if ($valeur === null) {
                    if ($operator === TableauBordFiltresOperators::OPERATEURSUPERIEUR 
                        || $operator === TableauBordFiltresOperators::OPERATEURINFERIEUR) {
                        $warning = true;
                    }
                }

                if ($valeur !== null) {
                    if ($checkDateFormat === false) {
                        $warning = true;
                    }
                }
                break;
            case Typeschamps::BOOLEANTYPE:
                if ($valeur !== 0 || $valeur !== 1) {
                    $warning = true;
                }
                break;
        }

        if ($warning === true) {
            return sprintf('%s (%s)', $property, $entitie);
        }

        return '';
    }

    public function createRequeteClauseWhere(): string
    {
        $getOperateurAndValeur = $this->formaterDonneesClauseWhere();

        return sprintf(' %s %s %s %s %s %s',
            $this->getTableauBordFiltreCondition()?->getLibelleAnglais(),
            $this->getIfParenthesOuvrante(),
            $this->getEntitiesPropriete()?->jointureName(),
            $getOperateurAndValeur['operator'],
            $getOperateurAndValeur['valeur'],
            $this->getIfParenthesFermante()
        );
    }

    public function formaterDonneesClauseWhere(): ?array
    {
        $operatorId  = $this->getTableauBordFiltreOperator()?->getId();
        $typeChamp   = $this->getEntitiesPropriete()?->getTypesChamps()?->getId();
        $valeur      = $this->getValeur();
        $newOperator = $this->getTableauBordFiltreOperator()?->getLibelleAnglais();
        $newValeur   = "'$valeur'";

        if ($operatorId === TableauBordFiltresOperators::OPERATEURCONTIENT) {
            $newValeur   = "'%$valeur%'";
        }

        if ($typeChamp === Typeschamps::DATETYPE && $valeur === null) {
            $newValeur   = '';
            
            if ($operatorId === TableauBordFiltresOperators::OPERATEUREGAL) {
                $newOperator = TableauBordFiltresOperators::OPERATEUR_EN_IS_NULL;
            }

            if ($operatorId === TableauBordFiltresOperators::OPERATEURDIFFERENT) {
                $newOperator = TableauBordFiltresOperators::OPERATEUR_EN_IS_NOT_NULL;
            }
        }

        return [
            'valeur'   => $newValeur,
            'operator' => $newOperator
        ];
    }
}
