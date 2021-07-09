<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210706112630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE beneficiaire ADD client_id INT NULL');
        $this->addSql('ALTER TABLE beneficiaire ADD CONSTRAINT FK_B140D80219EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B140D80219EB6921 ON beneficiaire (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE beneficiaire DROP FOREIGN KEY FK_B140D80219EB6921');
        $this->addSql('DROP INDEX IDX_B140D80219EB6921 ON beneficiaire');
        $this->addSql('ALTER TABLE beneficiaire DROP client_id');
    }
}
