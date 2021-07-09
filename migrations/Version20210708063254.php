<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210708063254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE FULLTEXT INDEX IDX_2DA179775E237E06E7927C74 ON user (name, email)');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d649e7927c74 TO UNIQ_2DA17977E7927C74');
        $this->addSql('ALTER TABLE user RENAME INDEX idx_8d93d64998260155 TO IDX_2DA1797798260155');
        $this->addSql('ALTER TABLE beneficiaire CHANGE client_id client_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE beneficiaire CHANGE client_id client_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_2DA179775E237E06E7927C74 ON User');
        $this->addSql('ALTER TABLE User RENAME INDEX idx_2da1797798260155 TO IDX_8D93D64998260155');
        $this->addSql('ALTER TABLE User RENAME INDEX uniq_2da17977e7927c74 TO UNIQ_8D93D649E7927C74');
    }
}
