<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210803131043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE beneficiaire ADD nom_du_site_beneficiaire_operation VARCHAR(255) DEFAULT NULL, ADD raison_social_du_beneficiaire_operation VARCHAR(255) DEFAULT NULL, ADD siren_beneficiaire_operation VARCHAR(255) DEFAULT NULL, ADD adresse_du_siege_social VARCHAR(255) DEFAULT NULL, ADD codepostal_siege_social VARCHAR(255) DEFAULT NULL, ADD ville_siege_social VARCHAR(255) DEFAULT NULL, ADD date_achevement_operation VARCHAR(255) DEFAULT NULL, ADD personne_morale TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE beneficiaire DROP nom_du_site_beneficiaire_operation, DROP raison_social_du_beneficiaire_operation, DROP siren_beneficiaire_operation, DROP adresse_du_siege_social, DROP codepostal_siege_social, DROP ville_siege_social, DROP date_achevement_operation, DROP personne_morale');
    }
}
