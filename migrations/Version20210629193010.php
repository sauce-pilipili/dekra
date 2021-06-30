<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210629193010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE controleur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE controleur_departements (controleur_id INT NOT NULL, departements_id INT NOT NULL, INDEX IDX_B0426F0AB13E6101 (controleur_id), INDEX IDX_B0426F0A1DB279A6 (departements_id), PRIMARY KEY(controleur_id, departements_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE controleur_specialite (controleur_id INT NOT NULL, specialite_id INT NOT NULL, INDEX IDX_BFBFB47AB13E6101 (controleur_id), INDEX IDX_BFBFB47A2195E0F0 (specialite_id), PRIMARY KEY(controleur_id, specialite_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departements (id INT AUTO_INCREMENT NOT NULL, numero VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialite (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, rgpd TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D64998260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE controleur_departements ADD CONSTRAINT FK_B0426F0AB13E6101 FOREIGN KEY (controleur_id) REFERENCES controleur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE controleur_departements ADD CONSTRAINT FK_B0426F0A1DB279A6 FOREIGN KEY (departements_id) REFERENCES departements (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE controleur_specialite ADD CONSTRAINT FK_BFBFB47AB13E6101 FOREIGN KEY (controleur_id) REFERENCES controleur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE controleur_specialite ADD CONSTRAINT FK_BFBFB47A2195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64998260155 FOREIGN KEY (region_id) REFERENCES region (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE controleur_departements DROP FOREIGN KEY FK_B0426F0AB13E6101');
        $this->addSql('ALTER TABLE controleur_specialite DROP FOREIGN KEY FK_BFBFB47AB13E6101');
        $this->addSql('ALTER TABLE controleur_departements DROP FOREIGN KEY FK_B0426F0A1DB279A6');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64998260155');
        $this->addSql('ALTER TABLE controleur_specialite DROP FOREIGN KEY FK_BFBFB47A2195E0F0');
        $this->addSql('DROP TABLE controleur');
        $this->addSql('DROP TABLE controleur_departements');
        $this->addSql('DROP TABLE controleur_specialite');
        $this->addSql('DROP TABLE departements');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE specialite');
        $this->addSql('DROP TABLE user');
    }
}
