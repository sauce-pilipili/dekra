<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220216080735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reference ADD client_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_AEA3491319EB6921 ON reference (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE beneficiaire DROP FOREIGN KEY FK_B140D80219EB6921');
        $this->addSql('ALTER TABLE beneficiaire CHANGE name name VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE prenom prenom VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE adresse adresse VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE code_postal code_postal VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE ville ville VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE raison_sociale_demandeur raison_sociale_demandeur VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE siren_demandeur siren_demandeur VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE reference_emmy_demande reference_emmy_demande VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE reference_interne reference_interne VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE volume_hors_precarite volume_hors_precarite VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE volume_precarite volume_precarite VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE reference_fiche_operation reference_fiche_operation VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE date_engagement_operation date_engagement_operation VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE date_facture date_facture VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE nature_bonification nature_bonification VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE siren_du_profesionnel siren_du_profesionnel VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE raison_social_du_professionnel raison_social_du_professionnel VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE siren_sous_traitant siren_sous_traitant VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE raison_sociale_sous_traitant raison_sociale_sous_traitant VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE nature_du_role_actif_incitatif nature_du_role_actif_incitatif VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE siren_organisme_controle siren_organisme_controle VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE raison_sociale_organisme_controle raison_sociale_organisme_controle VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE siret_entreprise_ayant_realise_operation siret_entreprise_ayant_realise_operation VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE action_corrective_menee_suite_audit action_corrective_menee_suite_audit VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE conformite_apres_correction conformite_apres_correction VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE operation_retire_ou_issue_dossier_precedent operation_retire_ou_issue_dossier_precedent VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE commentaire_generaux commentaire_generaux VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE grand_precaire_precaire_classique grand_precaire_precaire_classique VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE version_coup_de_pouce version_coup_de_pouce VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE email email VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE telephone telephone VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE nom_du_site_beneficiaire_operation nom_du_site_beneficiaire_operation VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE raison_social_du_beneficiaire_operation raison_social_du_beneficiaire_operation VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE siren_beneficiaire_operation siren_beneficiaire_operation VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE adresse_du_siege_social adresse_du_siege_social VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE codepostal_siege_social codepostal_siege_social VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE ville_siege_social ville_siege_social VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE date_achevement_operation date_achevement_operation VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE numero_lot numero_lot VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE surface_declaree_dans_ahfacture surface_declaree_dans_ahfacture VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE type_isolant_declare type_isolant_declare VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE marque_et_reference_isolant_declare marque_et_reference_isolant_declare VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE dekra_id dekra_id VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE beneficiaire_departements DROP FOREIGN KEY FK_18BFE4AA5AF81F68');
        $this->addSql('ALTER TABLE beneficiaire_departements DROP FOREIGN KEY FK_18BFE4AA1DB279A6');
        $this->addSql('ALTER TABLE controleur CHANGE nom nom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE prenom prenom VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE telnumber telnumber VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE controleur_departements DROP FOREIGN KEY FK_B0426F0AB13E6101');
        $this->addSql('ALTER TABLE controleur_departements DROP FOREIGN KEY FK_B0426F0A1DB279A6');
        $this->addSql('ALTER TABLE controleur_specialite DROP FOREIGN KEY FK_BFBFB47AB13E6101');
        $this->addSql('ALTER TABLE controleur_specialite DROP FOREIGN KEY FK_BFBFB47A2195E0F0');
        $this->addSql('ALTER TABLE departements CHANGE numero numero VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE reference DROP FOREIGN KEY FK_AEA3491319EB6921');
        $this->addSql('DROP INDEX IDX_AEA3491319EB6921 ON reference');
        $this->addSql('ALTER TABLE reference DROP client_id, CHANGE reference reference VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE id_lot_unique id_lot_unique VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE id_kizeo_form id_kizeo_form VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE region CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE reset_password_request CHANGE selector selector VARCHAR(20) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE hashed_token hashed_token VARCHAR(100) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE specialite CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE reference_operation reference_operation VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE User DROP FOREIGN KEY FK_2DA1797798260155');
        $this->addSql('ALTER TABLE User CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE telephone telephone VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE numero_lot numero_lot VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE user_user DROP FOREIGN KEY FK_F7129A803AD8644E');
        $this->addSql('ALTER TABLE user_user DROP FOREIGN KEY FK_F7129A80233D34C1');
    }
}
