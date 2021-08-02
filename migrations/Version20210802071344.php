<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210802071344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE beneficiaire ADD raison_sociale_demandeur VARCHAR(255) DEFAULT NULL, ADD siren_demandeur VARCHAR(255) DEFAULT NULL, ADD reference_emmy_demande VARCHAR(255) DEFAULT NULL, ADD reference_interne VARCHAR(255) NOT NULL, ADD volume_hors_precarite VARCHAR(255) DEFAULT NULL, ADD volume_precarite VARCHAR(255) DEFAULT NULL, ADD reference_fiche_operation VARCHAR(255) NOT NULL, ADD date_engagement_operation VARCHAR(255) DEFAULT NULL, ADD date_facture VARCHAR(255) DEFAULT NULL, ADD nature_bonification VARCHAR(255) DEFAULT NULL, ADD siren_du_profesionnel VARCHAR(255) DEFAULT NULL, ADD raison_social_du_professionnel VARCHAR(255) DEFAULT NULL, ADD siren_sous_traitant VARCHAR(255) DEFAULT NULL, ADD raison_sociale_sous_traitant VARCHAR(255) DEFAULT NULL, ADD nature_du_role_actif_incitatif VARCHAR(255) DEFAULT NULL, ADD siren_organisme_controle VARCHAR(255) DEFAULT NULL, ADD raison_sociale_organisme_controle VARCHAR(255) DEFAULT NULL, ADD siret_entreprise_ayant_realise_operation VARCHAR(255) DEFAULT NULL, ADD action_corrective_menee_suite_audit VARCHAR(255) DEFAULT NULL, ADD conformite_apres_correction VARCHAR(255) DEFAULT NULL, ADD operation_retire_ou_issue_dossier_precedent VARCHAR(255) DEFAULT NULL, ADD commentaire_generaux VARCHAR(255) DEFAULT NULL, ADD grand_precaire_precaire_classique VARCHAR(255) DEFAULT NULL, ADD version_coup_de_pouce VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE beneficiaire DROP raison_sociale_demandeur, DROP siren_demandeur, DROP reference_emmy_demande, DROP reference_interne, DROP volume_hors_precarite, DROP volume_precarite, DROP reference_fiche_operation, DROP date_engagement_operation, DROP date_facture, DROP nature_bonification, DROP siren_du_profesionnel, DROP raison_social_du_professionnel, DROP siren_sous_traitant, DROP raison_sociale_sous_traitant, DROP nature_du_role_actif_incitatif, DROP siren_organisme_controle, DROP raison_sociale_organisme_controle, DROP siret_entreprise_ayant_realise_operation, DROP action_corrective_menee_suite_audit, DROP conformite_apres_correction, DROP operation_retire_ou_issue_dossier_precedent, DROP commentaire_generaux, DROP grand_precaire_precaire_classique, DROP version_coup_de_pouce');
    }
}
