<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250906230927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE assignment_person_id_seq CASCADE');
        $this->addSql('CREATE TABLE assignment_person (assignment_id INT NOT NULL, person_id INT NOT NULL, PRIMARY KEY(assignment_id, person_id))');
        $this->addSql('CREATE INDEX IDX_CFDF16BD19302F8 ON assignment_person (assignment_id)');
        $this->addSql('CREATE INDEX IDX_CFDF16B217BBB47 ON assignment_person (person_id)');
        $this->addSql('ALTER TABLE assignment_person ADD CONSTRAINT FK_CFDF16BD19302F8 FOREIGN KEY (assignment_id) REFERENCES assignment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE assignment_person ADD CONSTRAINT FK_CFDF16B217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE assignment DROP CONSTRAINT fk_30c544ba41807e1d');
        $this->addSql('ALTER TABLE assignment DROP CONSTRAINT fk_30c544ba217bbb47');
        $this->addSql('DROP INDEX idx_30c544ba217bbb47');
        $this->addSql('DROP INDEX idx_30c544ba41807e1d');
        $this->addSql('ALTER TABLE assignment DROP teacher_id');
        $this->addSql('ALTER TABLE assignment DROP person_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE assignment_person_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP TABLE assignment_person');
        $this->addSql('ALTER TABLE assignment ADD teacher_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE assignment ADD person_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE assignment ADD CONSTRAINT fk_30c544ba41807e1d FOREIGN KEY (teacher_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE assignment ADD CONSTRAINT fk_30c544ba217bbb47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_30c544ba217bbb47 ON assignment (person_id)');
        $this->addSql('CREATE INDEX idx_30c544ba41807e1d ON assignment (teacher_id)');
    }
}
