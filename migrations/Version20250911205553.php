<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250911205553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE set ADD log_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE set ADD CONSTRAINT FK_E61425DCEA675D86 FOREIGN KEY (log_id) REFERENCES log (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E61425DCEA675D86 ON set (log_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE set DROP CONSTRAINT FK_E61425DCEA675D86');
        $this->addSql('DROP INDEX UNIQ_E61425DCEA675D86');
        $this->addSql('ALTER TABLE set DROP log_id');
    }
}
