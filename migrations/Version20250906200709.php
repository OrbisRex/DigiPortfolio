<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250906200709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE person_set (person_id INT NOT NULL, set_id INT NOT NULL, PRIMARY KEY(person_id, set_id))');
        $this->addSql('CREATE INDEX IDX_6559E2C7217BBB47 ON person_set (person_id)');
        $this->addSql('CREATE INDEX IDX_6559E2C710FB0D18 ON person_set (set_id)');
        $this->addSql('ALTER TABLE person_set ADD CONSTRAINT FK_6559E2C7217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE person_set ADD CONSTRAINT FK_6559E2C710FB0D18 FOREIGN KEY (set_id) REFERENCES set (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE people_sets');
        $this->addSql('DROP TABLE person_submission');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE people_sets (person_id INT NOT NULL, set_id INT NOT NULL, PRIMARY KEY(person_id, set_id))');
        $this->addSql('CREATE INDEX idx_9644896110fb0d18 ON people_sets (set_id)');
        $this->addSql('CREATE INDEX idx_96448961217bbb47 ON people_sets (person_id)');
        $this->addSql('CREATE TABLE person_submission (person_id INT NOT NULL, submission_id INT NOT NULL, PRIMARY KEY(person_id, submission_id))');
        $this->addSql('CREATE INDEX idx_edbe39e6217bbb47 ON person_submission (person_id)');
        $this->addSql('CREATE INDEX idx_edbe39e6e1fd4933 ON person_submission (submission_id)');
        $this->addSql('ALTER TABLE people_sets ADD CONSTRAINT fk_96448961217bbb47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE people_sets ADD CONSTRAINT fk_9644896110fb0d18 FOREIGN KEY (set_id) REFERENCES set (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE person_submission ADD CONSTRAINT fk_edbe39e6217bbb47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE person_submission ADD CONSTRAINT fk_edbe39e6e1fd4933 FOREIGN KEY (submission_id) REFERENCES submission (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE person_set');
    }
}
