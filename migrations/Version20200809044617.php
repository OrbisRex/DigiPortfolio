<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200809044617 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE assignment_criterion (assignment_id INT NOT NULL, criterion_id INT NOT NULL, PRIMARY KEY(assignment_id, criterion_id))');
        $this->addSql('CREATE INDEX IDX_B1912696D19302F8 ON assignment_criterion (assignment_id)');
        $this->addSql('CREATE INDEX IDX_B191269697766307 ON assignment_criterion (criterion_id)');
        $this->addSql('ALTER TABLE assignment_criterion ADD CONSTRAINT FK_B1912696D19302F8 FOREIGN KEY (assignment_id) REFERENCES assignment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE assignment_criterion ADD CONSTRAINT FK_B191269697766307 FOREIGN KEY (criterion_id) REFERENCES criterion (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE assignment_criteria');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE assignment_criteria (assignment_id INT NOT NULL, criterion_id INT NOT NULL, PRIMARY KEY(assignment_id, criterion_id))');
        $this->addSql('CREATE INDEX idx_721def7497766307 ON assignment_criteria (criterion_id)');
        $this->addSql('CREATE INDEX idx_721def74d19302f8 ON assignment_criteria (assignment_id)');
        $this->addSql('ALTER TABLE assignment_criteria ADD CONSTRAINT fk_721def74d19302f8 FOREIGN KEY (assignment_id) REFERENCES assignment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE assignment_criteria ADD CONSTRAINT fk_721def7497766307 FOREIGN KEY (criterion_id) REFERENCES criterion (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE assignment_criterion');
    }
}
