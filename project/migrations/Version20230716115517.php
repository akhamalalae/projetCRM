<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230716115517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Attachement (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, fileName VARCHAR(255) DEFAULT NULL, fileSize INT DEFAULT NULL, updatedAt DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', contentUrl VARCHAR(255) DEFAULT NULL, INDEX IDX_168096B6F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE BaseClients (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE CategorieProduits (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, ordre INT DEFAULT NULL, status TINYINT(1) DEFAULT NULL, date_creation DATETIME DEFAULT NULL, date_modification DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ChampsFormulaire (id INT AUTO_INCREMENT NOT NULL, formulaire_id INT NOT NULL, type_id INT DEFAULT NULL, referentiels_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, status TINYINT(1) NOT NULL, dateCreation DATETIME NOT NULL, dateModification DATETIME NOT NULL, ordre INT NOT NULL, INDEX IDX_8A378B4E5053569B (formulaire_id), INDEX IDX_8A378B4EC54C8C93 (type_id), INDEX IDX_8A378B4EB8F4689C (referentiels_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ConfigurationEspace (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, x INT DEFAULT NULL, y INT DEFAULT NULL, color VARCHAR(255) NOT NULL, dateModification DATETIME DEFAULT NULL, dateCreation DATETIME DEFAULT NULL, pointVente_id INT DEFAULT NULL, INDEX IDX_33BA5006472D6DED (pointVente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ConfigurationObjet (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, dateCreation DATETIME DEFAULT NULL, dateModification DATETIME DEFAULT NULL, configurationEspace_id INT DEFAULT NULL, INDEX IDX_E23CDD107B5C2CB4 (configurationEspace_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Departement (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, code INT NOT NULL, INDEX IDX_47EAD4B498260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE EnregistrementFormulaire (id INT AUTO_INCREMENT NOT NULL, intervenant_id INT DEFAULT NULL, formulaires_id INT DEFAULT NULL, calander_rendez_vous_id INT DEFAULT NULL, resultats JSON NOT NULL, dateCreation DATETIME DEFAULT NULL, dateModification DATETIME DEFAULT NULL, INDEX IDX_9AD5CC6DAB9A1716 (intervenant_id), INDEX IDX_9AD5CC6D80FCA4BF (formulaires_id), INDEX IDX_9AD5CC6D1E88ED6C (calander_rendez_vous_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE enregistrementformulaire_files (enregistrementformulaire_id INT NOT NULL, files_id INT NOT NULL, INDEX IDX_36BC26FB82F3E9F (enregistrementformulaire_id), INDEX IDX_36BC26FA3E65B2F (files_id), PRIMARY KEY(enregistrementformulaire_id, files_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Entities (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, status TINYINT(1) DEFAULT NULL, nomProprieteeJointure VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE EntitiesPropriete (id INT AUTO_INCREMENT NOT NULL, entitie_id INT DEFAULT NULL, entitie_joiture_id INT DEFAULT NULL, libelle VARCHAR(255) DEFAULT NULL, methode VARCHAR(255) DEFAULT NULL, status TINYINT(1) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, typesChamps_id INT DEFAULT NULL, INDEX IDX_337445BBE7B82AD0 (entitie_id), INDEX IDX_337445BB634C9A66 (typesChamps_id), INDEX IDX_337445BB977B1BC8 (entitie_joiture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Entreprise (id INT AUTO_INCREMENT NOT NULL, ville_id INT DEFAULT NULL, dateCreationEntreprise DATETIME DEFAULT NULL, formeJuridique VARCHAR(255) DEFAULT NULL, nomsCommerciaux VARCHAR(255) DEFAULT NULL, numeroSIREN VARCHAR(255) DEFAULT NULL, NumeroSIRET VARCHAR(255) DEFAULT NULL, numerosRCS VARCHAR(255) DEFAULT NULL, dateImmatriculationRCS DATETIME DEFAULT NULL, dateEnregistrementINSEE DATETIME DEFAULT NULL, capitalSocial VARCHAR(255) DEFAULT NULL, dateCreation DATETIME DEFAULT NULL, dateModification DATETIME DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, complementAdresse VARCHAR(255) DEFAULT NULL, INDEX IDX_4244F9B0A73F0036 (ville_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Files (id INT AUTO_INCREMENT NOT NULL, file VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, dateCreation DATETIME DEFAULT NULL, dateModification DATETIME DEFAULT NULL, champsFormulaire_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_C7F46F5D8C9F3610 (file), INDEX IDX_C7F46F5D5A0BF7EB (champsFormulaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Formulaire (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, dateDebut DATETIME NOT NULL, dateFin DATETIME NOT NULL, status TINYINT(1) DEFAULT NULL, dateCreation DATETIME DEFAULT NULL, dateModification DATETIME DEFAULT NULL, INDEX IDX_14800278A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formulaire_user (formulaire_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_F443379C5053569B (formulaire_id), INDEX IDX_F443379CA76ED395 (user_id), PRIMARY KEY(formulaire_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formulaire_entreprise (formulaire_id INT NOT NULL, entreprise_id INT NOT NULL, INDEX IDX_272A73D55053569B (formulaire_id), INDEX IDX_272A73D5A4AEAFEA (entreprise_id), PRIMARY KEY(formulaire_id, entreprise_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE GroupUsers (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, status TINYINT(1) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, dateCreation DATETIME DEFAULT NULL, dateModification DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE HistoriqueGenerationAutomatiqueRouting (id INT AUTO_INCREMENT NOT NULL, dateDebut DATETIME DEFAULT NULL, dateCreation DATETIME DEFAULT NULL, ecartEnMunites VARCHAR(255) DEFAULT NULL, dateModification DATETIME DEFAULT NULL, userCreateur_id INT DEFAULT NULL, INDEX IDX_254A2CAF3112B8A6 (userCreateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historiquegenerationautomatiquerouting_formulaire (historiquegenerationautomatiquerouting_id INT NOT NULL, formulaire_id INT NOT NULL, INDEX IDX_F39DFD4771A65631 (historiquegenerationautomatiquerouting_id), INDEX IDX_F39DFD475053569B (formulaire_id), PRIMARY KEY(historiquegenerationautomatiquerouting_id, formulaire_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE MenuCategorie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, ordre INT DEFAULT NULL, icone VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, statut TINYINT(1) DEFAULT NULL, date_creation DATETIME DEFAULT NULL, date_modification DATETIME DEFAULT NULL, access VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE MenuSousCategorie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, ordre INT DEFAULT NULL, icone VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, statut TINYINT(1) DEFAULT NULL, date_creation DATETIME DEFAULT NULL, date_modification DATETIME DEFAULT NULL, access VARCHAR(255) DEFAULT NULL, menuCategorie_id INT DEFAULT NULL, INDEX IDX_5586CFA192859067 (menuCategorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Options (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, ordre INT NOT NULL, status TINYINT(1) NOT NULL, dateCreation DATETIME NOT NULL, dateModification DATETIME NOT NULL, champsFormulaire_id INT NOT NULL, INDEX IDX_1F88C31B5A0BF7EB (champsFormulaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE PointVente (id INT AUTO_INCREMENT NOT NULL, ville_id INT DEFAULT NULL, libelle VARCHAR(255) DEFAULT NULL, status TINYINT(1) DEFAULT NULL, dateCreation DATETIME DEFAULT NULL, dateModification DATETIME DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, complementAdresse VARCHAR(255) DEFAULT NULL, INDEX IDX_D2B391E1A73F0036 (ville_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pointvente_entreprise (pointvente_id INT NOT NULL, entreprise_id INT NOT NULL, INDEX IDX_13A64634BE5B0FBB (pointvente_id), INDEX IDX_13A64634A4AEAFEA (entreprise_id), PRIMARY KEY(pointvente_id, entreprise_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Produit (id INT AUTO_INCREMENT NOT NULL, entreprises_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, status TINYINT(1) DEFAULT NULL, dateCreation DATETIME DEFAULT NULL, dateModification DATETIME DEFAULT NULL, INDEX IDX_E618D5BBA70A18EC (entreprises_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_categorieproduits (produit_id INT NOT NULL, categorieproduits_id INT NOT NULL, INDEX IDX_7676023EF347EFB (produit_id), INDEX IDX_7676023E3093981B (categorieproduits_id), PRIMARY KEY(produit_id, categorieproduits_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Referentiels (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, status TINYINT(1) DEFAULT NULL, dateCreation DATETIME DEFAULT NULL, dateModification DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ReferentielsOptions (id INT AUTO_INCREMENT NOT NULL, referentiels_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, status TINYINT(1) NOT NULL, dateCreation DATETIME DEFAULT NULL, dateModification DATETIME DEFAULT NULL, INDEX IDX_17C878AFB8F4689C (referentiels_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE RenderVous (id INT AUTO_INCREMENT NOT NULL, formulaire_id INT DEFAULT NULL, intervenant_id INT DEFAULT NULL, entreprise_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, description LONGTEXT NOT NULL, allDay TINYINT(1) DEFAULT NULL, backgroundColor VARCHAR(7) DEFAULT NULL, borderColor VARCHAR(7) DEFAULT NULL, textColor VARCHAR(7) DEFAULT NULL, effectuer TINYINT(1) DEFAULT NULL, dateCreation DATETIME DEFAULT NULL, dateModification DATETIME DEFAULT NULL, pointeVente_id INT DEFAULT NULL, historiqueGenerationAutomatiqueRouting_id INT DEFAULT NULL, userCreateur_id INT DEFAULT NULL, INDEX IDX_1F857C87ADCB6572 (pointeVente_id), INDEX IDX_1F857C875053569B (formulaire_id), INDEX IDX_1F857C87AEBF6C58 (historiqueGenerationAutomatiqueRouting_id), INDEX IDX_1F857C87AB9A1716 (intervenant_id), INDEX IDX_1F857C873112B8A6 (userCreateur_id), INDEX IDX_1F857C87A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE RequeteTableauBord (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, enregistrer_requete TINYINT(1) NOT NULL, dateCreation DATETIME DEFAULT NULL, dateModification DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE requetetableaubord_entitiespropriete (requetetableaubord_id INT NOT NULL, entitiespropriete_id INT NOT NULL, INDEX IDX_A68ED3E49B22D6D4 (requetetableaubord_id), INDEX IDX_A68ED3E477E15164 (entitiespropriete_id), PRIMARY KEY(requetetableaubord_id, entitiespropriete_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE RequeteTableauBordFiltres (id INT AUTO_INCREMENT NOT NULL, requete_tableau_bord_id INT DEFAULT NULL, tableau_bord_filtre_condition_id INT DEFAULT NULL, tableau_bord_filtre_operator_id INT DEFAULT NULL, entities_propriete_id INT DEFAULT NULL, entitie_id INT DEFAULT NULL, valeur VARCHAR(255) NOT NULL, dateCreation DATETIME DEFAULT NULL, dateModification DATETIME DEFAULT NULL, INDEX IDX_A756BCA291E92B7 (requete_tableau_bord_id), INDEX IDX_A756BCAF740BD96 (tableau_bord_filtre_condition_id), INDEX IDX_A756BCA56603EF (tableau_bord_filtre_operator_id), INDEX IDX_A756BCA4ED4A203 (entities_propriete_id), INDEX IDX_A756BCAE7B82AD0 (entitie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE TableauBordFiltresConditions (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE TableauBordFiltresOperators (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Typeschamps (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, ordre TINYINT(1) NOT NULL, status TINYINT(1) NOT NULL, libelleAnglais VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE User (id INT AUTO_INCREMENT NOT NULL, ville_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, dateCreation DATETIME DEFAULT NULL, dateModification DATETIME DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, complementAdresse VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_2DA17977E7927C74 (email), INDEX IDX_2DA17977A73F0036 (ville_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_groupusers (user_id INT NOT NULL, groupusers_id INT NOT NULL, INDEX IDX_C79B486EA76ED395 (user_id), INDEX IDX_C79B486E95FB014C (groupusers_id), PRIMARY KEY(user_id, groupusers_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Ville (id INT AUTO_INCREMENT NOT NULL, departement_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, code INT NOT NULL, INDEX IDX_8202F6C7CCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Attachement ADD CONSTRAINT FK_168096B6F347EFB FOREIGN KEY (produit_id) REFERENCES Produit (id)');
        $this->addSql('ALTER TABLE ChampsFormulaire ADD CONSTRAINT FK_8A378B4E5053569B FOREIGN KEY (formulaire_id) REFERENCES Formulaire (id)');
        $this->addSql('ALTER TABLE ChampsFormulaire ADD CONSTRAINT FK_8A378B4EC54C8C93 FOREIGN KEY (type_id) REFERENCES Typeschamps (id)');
        $this->addSql('ALTER TABLE ChampsFormulaire ADD CONSTRAINT FK_8A378B4EB8F4689C FOREIGN KEY (referentiels_id) REFERENCES Referentiels (id)');
        $this->addSql('ALTER TABLE ConfigurationEspace ADD CONSTRAINT FK_33BA5006472D6DED FOREIGN KEY (pointVente_id) REFERENCES PointVente (id)');
        $this->addSql('ALTER TABLE ConfigurationObjet ADD CONSTRAINT FK_E23CDD107B5C2CB4 FOREIGN KEY (configurationEspace_id) REFERENCES ConfigurationEspace (id)');
        $this->addSql('ALTER TABLE Departement ADD CONSTRAINT FK_47EAD4B498260155 FOREIGN KEY (region_id) REFERENCES Region (id)');
        $this->addSql('ALTER TABLE EnregistrementFormulaire ADD CONSTRAINT FK_9AD5CC6DAB9A1716 FOREIGN KEY (intervenant_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE EnregistrementFormulaire ADD CONSTRAINT FK_9AD5CC6D80FCA4BF FOREIGN KEY (formulaires_id) REFERENCES Formulaire (id)');
        $this->addSql('ALTER TABLE EnregistrementFormulaire ADD CONSTRAINT FK_9AD5CC6D1E88ED6C FOREIGN KEY (calander_rendez_vous_id) REFERENCES RenderVous (id)');
        $this->addSql('ALTER TABLE enregistrementformulaire_files ADD CONSTRAINT FK_36BC26FB82F3E9F FOREIGN KEY (enregistrementformulaire_id) REFERENCES EnregistrementFormulaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE enregistrementformulaire_files ADD CONSTRAINT FK_36BC26FA3E65B2F FOREIGN KEY (files_id) REFERENCES Files (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE EntitiesPropriete ADD CONSTRAINT FK_337445BBE7B82AD0 FOREIGN KEY (entitie_id) REFERENCES Entities (id)');
        $this->addSql('ALTER TABLE EntitiesPropriete ADD CONSTRAINT FK_337445BB634C9A66 FOREIGN KEY (typesChamps_id) REFERENCES Typeschamps (id)');
        $this->addSql('ALTER TABLE EntitiesPropriete ADD CONSTRAINT FK_337445BB977B1BC8 FOREIGN KEY (entitie_joiture_id) REFERENCES Entities (id)');
        $this->addSql('ALTER TABLE Entreprise ADD CONSTRAINT FK_4244F9B0A73F0036 FOREIGN KEY (ville_id) REFERENCES Ville (id)');
        $this->addSql('ALTER TABLE Files ADD CONSTRAINT FK_C7F46F5D5A0BF7EB FOREIGN KEY (champsFormulaire_id) REFERENCES ChampsFormulaire (id)');
        $this->addSql('ALTER TABLE Formulaire ADD CONSTRAINT FK_14800278A76ED395 FOREIGN KEY (user_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE formulaire_user ADD CONSTRAINT FK_F443379C5053569B FOREIGN KEY (formulaire_id) REFERENCES Formulaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formulaire_user ADD CONSTRAINT FK_F443379CA76ED395 FOREIGN KEY (user_id) REFERENCES User (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formulaire_entreprise ADD CONSTRAINT FK_272A73D55053569B FOREIGN KEY (formulaire_id) REFERENCES Formulaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formulaire_entreprise ADD CONSTRAINT FK_272A73D5A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES Entreprise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE HistoriqueGenerationAutomatiqueRouting ADD CONSTRAINT FK_254A2CAF3112B8A6 FOREIGN KEY (userCreateur_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE historiquegenerationautomatiquerouting_formulaire ADD CONSTRAINT FK_F39DFD4771A65631 FOREIGN KEY (historiquegenerationautomatiquerouting_id) REFERENCES HistoriqueGenerationAutomatiqueRouting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE historiquegenerationautomatiquerouting_formulaire ADD CONSTRAINT FK_F39DFD475053569B FOREIGN KEY (formulaire_id) REFERENCES Formulaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE MenuSousCategorie ADD CONSTRAINT FK_5586CFA192859067 FOREIGN KEY (menuCategorie_id) REFERENCES MenuCategorie (id)');
        $this->addSql('ALTER TABLE Options ADD CONSTRAINT FK_1F88C31B5A0BF7EB FOREIGN KEY (champsFormulaire_id) REFERENCES ChampsFormulaire (id)');
        $this->addSql('ALTER TABLE PointVente ADD CONSTRAINT FK_D2B391E1A73F0036 FOREIGN KEY (ville_id) REFERENCES Ville (id)');
        $this->addSql('ALTER TABLE pointvente_entreprise ADD CONSTRAINT FK_13A64634BE5B0FBB FOREIGN KEY (pointvente_id) REFERENCES PointVente (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pointvente_entreprise ADD CONSTRAINT FK_13A64634A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES Entreprise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Produit ADD CONSTRAINT FK_E618D5BBA70A18EC FOREIGN KEY (entreprises_id) REFERENCES Entreprise (id)');
        $this->addSql('ALTER TABLE produit_categorieproduits ADD CONSTRAINT FK_7676023EF347EFB FOREIGN KEY (produit_id) REFERENCES Produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_categorieproduits ADD CONSTRAINT FK_7676023E3093981B FOREIGN KEY (categorieproduits_id) REFERENCES CategorieProduits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ReferentielsOptions ADD CONSTRAINT FK_17C878AFB8F4689C FOREIGN KEY (referentiels_id) REFERENCES Referentiels (id)');
        $this->addSql('ALTER TABLE RenderVous ADD CONSTRAINT FK_1F857C87ADCB6572 FOREIGN KEY (pointeVente_id) REFERENCES PointVente (id)');
        $this->addSql('ALTER TABLE RenderVous ADD CONSTRAINT FK_1F857C875053569B FOREIGN KEY (formulaire_id) REFERENCES Formulaire (id)');
        $this->addSql('ALTER TABLE RenderVous ADD CONSTRAINT FK_1F857C87AEBF6C58 FOREIGN KEY (historiqueGenerationAutomatiqueRouting_id) REFERENCES HistoriqueGenerationAutomatiqueRouting (id)');
        $this->addSql('ALTER TABLE RenderVous ADD CONSTRAINT FK_1F857C87AB9A1716 FOREIGN KEY (intervenant_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE RenderVous ADD CONSTRAINT FK_1F857C873112B8A6 FOREIGN KEY (userCreateur_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE RenderVous ADD CONSTRAINT FK_1F857C87A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES Entreprise (id)');
        $this->addSql('ALTER TABLE requetetableaubord_entitiespropriete ADD CONSTRAINT FK_A68ED3E49B22D6D4 FOREIGN KEY (requetetableaubord_id) REFERENCES RequeteTableauBord (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE requetetableaubord_entitiespropriete ADD CONSTRAINT FK_A68ED3E477E15164 FOREIGN KEY (entitiespropriete_id) REFERENCES EntitiesPropriete (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE RequeteTableauBordFiltres ADD CONSTRAINT FK_A756BCA291E92B7 FOREIGN KEY (requete_tableau_bord_id) REFERENCES RequeteTableauBord (id)');
        $this->addSql('ALTER TABLE RequeteTableauBordFiltres ADD CONSTRAINT FK_A756BCAF740BD96 FOREIGN KEY (tableau_bord_filtre_condition_id) REFERENCES TableauBordFiltresConditions (id)');
        $this->addSql('ALTER TABLE RequeteTableauBordFiltres ADD CONSTRAINT FK_A756BCA56603EF FOREIGN KEY (tableau_bord_filtre_operator_id) REFERENCES TableauBordFiltresOperators (id)');
        $this->addSql('ALTER TABLE RequeteTableauBordFiltres ADD CONSTRAINT FK_A756BCA4ED4A203 FOREIGN KEY (entities_propriete_id) REFERENCES EntitiesPropriete (id)');
        $this->addSql('ALTER TABLE RequeteTableauBordFiltres ADD CONSTRAINT FK_A756BCAE7B82AD0 FOREIGN KEY (entitie_id) REFERENCES Entities (id)');
        $this->addSql('ALTER TABLE User ADD CONSTRAINT FK_2DA17977A73F0036 FOREIGN KEY (ville_id) REFERENCES Ville (id)');
        $this->addSql('ALTER TABLE user_groupusers ADD CONSTRAINT FK_C79B486EA76ED395 FOREIGN KEY (user_id) REFERENCES User (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_groupusers ADD CONSTRAINT FK_C79B486E95FB014C FOREIGN KEY (groupusers_id) REFERENCES GroupUsers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Ville ADD CONSTRAINT FK_8202F6C7CCF9E01E FOREIGN KEY (departement_id) REFERENCES Departement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_categorieproduits DROP FOREIGN KEY FK_7676023E3093981B');
        $this->addSql('ALTER TABLE Files DROP FOREIGN KEY FK_C7F46F5D5A0BF7EB');
        $this->addSql('ALTER TABLE Options DROP FOREIGN KEY FK_1F88C31B5A0BF7EB');
        $this->addSql('ALTER TABLE ConfigurationObjet DROP FOREIGN KEY FK_E23CDD107B5C2CB4');
        $this->addSql('ALTER TABLE Ville DROP FOREIGN KEY FK_8202F6C7CCF9E01E');
        $this->addSql('ALTER TABLE enregistrementformulaire_files DROP FOREIGN KEY FK_36BC26FB82F3E9F');
        $this->addSql('ALTER TABLE EntitiesPropriete DROP FOREIGN KEY FK_337445BBE7B82AD0');
        $this->addSql('ALTER TABLE EntitiesPropriete DROP FOREIGN KEY FK_337445BB977B1BC8');
        $this->addSql('ALTER TABLE RequeteTableauBordFiltres DROP FOREIGN KEY FK_A756BCAE7B82AD0');
        $this->addSql('ALTER TABLE requetetableaubord_entitiespropriete DROP FOREIGN KEY FK_A68ED3E477E15164');
        $this->addSql('ALTER TABLE RequeteTableauBordFiltres DROP FOREIGN KEY FK_A756BCA4ED4A203');
        $this->addSql('ALTER TABLE formulaire_entreprise DROP FOREIGN KEY FK_272A73D5A4AEAFEA');
        $this->addSql('ALTER TABLE pointvente_entreprise DROP FOREIGN KEY FK_13A64634A4AEAFEA');
        $this->addSql('ALTER TABLE Produit DROP FOREIGN KEY FK_E618D5BBA70A18EC');
        $this->addSql('ALTER TABLE RenderVous DROP FOREIGN KEY FK_1F857C87A4AEAFEA');
        $this->addSql('ALTER TABLE enregistrementformulaire_files DROP FOREIGN KEY FK_36BC26FA3E65B2F');
        $this->addSql('ALTER TABLE ChampsFormulaire DROP FOREIGN KEY FK_8A378B4E5053569B');
        $this->addSql('ALTER TABLE EnregistrementFormulaire DROP FOREIGN KEY FK_9AD5CC6D80FCA4BF');
        $this->addSql('ALTER TABLE formulaire_user DROP FOREIGN KEY FK_F443379C5053569B');
        $this->addSql('ALTER TABLE formulaire_entreprise DROP FOREIGN KEY FK_272A73D55053569B');
        $this->addSql('ALTER TABLE historiquegenerationautomatiquerouting_formulaire DROP FOREIGN KEY FK_F39DFD475053569B');
        $this->addSql('ALTER TABLE RenderVous DROP FOREIGN KEY FK_1F857C875053569B');
        $this->addSql('ALTER TABLE user_groupusers DROP FOREIGN KEY FK_C79B486E95FB014C');
        $this->addSql('ALTER TABLE historiquegenerationautomatiquerouting_formulaire DROP FOREIGN KEY FK_F39DFD4771A65631');
        $this->addSql('ALTER TABLE RenderVous DROP FOREIGN KEY FK_1F857C87AEBF6C58');
        $this->addSql('ALTER TABLE MenuSousCategorie DROP FOREIGN KEY FK_5586CFA192859067');
        $this->addSql('ALTER TABLE ConfigurationEspace DROP FOREIGN KEY FK_33BA5006472D6DED');
        $this->addSql('ALTER TABLE pointvente_entreprise DROP FOREIGN KEY FK_13A64634BE5B0FBB');
        $this->addSql('ALTER TABLE RenderVous DROP FOREIGN KEY FK_1F857C87ADCB6572');
        $this->addSql('ALTER TABLE Attachement DROP FOREIGN KEY FK_168096B6F347EFB');
        $this->addSql('ALTER TABLE produit_categorieproduits DROP FOREIGN KEY FK_7676023EF347EFB');
        $this->addSql('ALTER TABLE ChampsFormulaire DROP FOREIGN KEY FK_8A378B4EB8F4689C');
        $this->addSql('ALTER TABLE ReferentielsOptions DROP FOREIGN KEY FK_17C878AFB8F4689C');
        $this->addSql('ALTER TABLE Departement DROP FOREIGN KEY FK_47EAD4B498260155');
        $this->addSql('ALTER TABLE EnregistrementFormulaire DROP FOREIGN KEY FK_9AD5CC6D1E88ED6C');
        $this->addSql('ALTER TABLE requetetableaubord_entitiespropriete DROP FOREIGN KEY FK_A68ED3E49B22D6D4');
        $this->addSql('ALTER TABLE RequeteTableauBordFiltres DROP FOREIGN KEY FK_A756BCA291E92B7');
        $this->addSql('ALTER TABLE RequeteTableauBordFiltres DROP FOREIGN KEY FK_A756BCAF740BD96');
        $this->addSql('ALTER TABLE RequeteTableauBordFiltres DROP FOREIGN KEY FK_A756BCA56603EF');
        $this->addSql('ALTER TABLE ChampsFormulaire DROP FOREIGN KEY FK_8A378B4EC54C8C93');
        $this->addSql('ALTER TABLE EntitiesPropriete DROP FOREIGN KEY FK_337445BB634C9A66');
        $this->addSql('ALTER TABLE EnregistrementFormulaire DROP FOREIGN KEY FK_9AD5CC6DAB9A1716');
        $this->addSql('ALTER TABLE Formulaire DROP FOREIGN KEY FK_14800278A76ED395');
        $this->addSql('ALTER TABLE formulaire_user DROP FOREIGN KEY FK_F443379CA76ED395');
        $this->addSql('ALTER TABLE HistoriqueGenerationAutomatiqueRouting DROP FOREIGN KEY FK_254A2CAF3112B8A6');
        $this->addSql('ALTER TABLE RenderVous DROP FOREIGN KEY FK_1F857C87AB9A1716');
        $this->addSql('ALTER TABLE RenderVous DROP FOREIGN KEY FK_1F857C873112B8A6');
        $this->addSql('ALTER TABLE user_groupusers DROP FOREIGN KEY FK_C79B486EA76ED395');
        $this->addSql('ALTER TABLE Entreprise DROP FOREIGN KEY FK_4244F9B0A73F0036');
        $this->addSql('ALTER TABLE PointVente DROP FOREIGN KEY FK_D2B391E1A73F0036');
        $this->addSql('ALTER TABLE User DROP FOREIGN KEY FK_2DA17977A73F0036');
        $this->addSql('DROP TABLE Attachement');
        $this->addSql('DROP TABLE BaseClients');
        $this->addSql('DROP TABLE CategorieProduits');
        $this->addSql('DROP TABLE ChampsFormulaire');
        $this->addSql('DROP TABLE ConfigurationEspace');
        $this->addSql('DROP TABLE ConfigurationObjet');
        $this->addSql('DROP TABLE Departement');
        $this->addSql('DROP TABLE EnregistrementFormulaire');
        $this->addSql('DROP TABLE enregistrementformulaire_files');
        $this->addSql('DROP TABLE Entities');
        $this->addSql('DROP TABLE EntitiesPropriete');
        $this->addSql('DROP TABLE Entreprise');
        $this->addSql('DROP TABLE Files');
        $this->addSql('DROP TABLE Formulaire');
        $this->addSql('DROP TABLE formulaire_user');
        $this->addSql('DROP TABLE formulaire_entreprise');
        $this->addSql('DROP TABLE GroupUsers');
        $this->addSql('DROP TABLE HistoriqueGenerationAutomatiqueRouting');
        $this->addSql('DROP TABLE historiquegenerationautomatiquerouting_formulaire');
        $this->addSql('DROP TABLE MenuCategorie');
        $this->addSql('DROP TABLE MenuSousCategorie');
        $this->addSql('DROP TABLE Options');
        $this->addSql('DROP TABLE PointVente');
        $this->addSql('DROP TABLE pointvente_entreprise');
        $this->addSql('DROP TABLE Produit');
        $this->addSql('DROP TABLE produit_categorieproduits');
        $this->addSql('DROP TABLE Referentiels');
        $this->addSql('DROP TABLE ReferentielsOptions');
        $this->addSql('DROP TABLE Region');
        $this->addSql('DROP TABLE RenderVous');
        $this->addSql('DROP TABLE RequeteTableauBord');
        $this->addSql('DROP TABLE requetetableaubord_entitiespropriete');
        $this->addSql('DROP TABLE RequeteTableauBordFiltres');
        $this->addSql('DROP TABLE TableauBordFiltresConditions');
        $this->addSql('DROP TABLE TableauBordFiltresOperators');
        $this->addSql('DROP TABLE Typeschamps');
        $this->addSql('DROP TABLE User');
        $this->addSql('DROP TABLE user_groupusers');
        $this->addSql('DROP TABLE Ville');
    }
}
