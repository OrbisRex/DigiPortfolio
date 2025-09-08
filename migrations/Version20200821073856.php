<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200821073856 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE submission_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE submission (id INT NOT NULL, owner_id INT NOT NULL, assignment_id INT NOT NULL, log_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, note VARCHAR(255) DEFAULT NULL, version INT NOT NULL, link VARCHAR(1024) DEFAULT NULL, text TEXT DEFAULT NULL, updatetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DB055AF37E3C61F9 ON submission (owner_id)');
        $this->addSql('CREATE INDEX IDX_DB055AF3D19302F8 ON submission (assignment_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DB055AF3EA675D86 ON submission (log_id)');
        $this->addSql('CREATE TABLE submission_criterion (submission_id INT NOT NULL, criterion_id INT NOT NULL, PRIMARY KEY(submission_id, criterion_id))');
        $this->addSql('CREATE INDEX IDX_CC3D26C5E1FD4933 ON submission_criterion (submission_id)');
        $this->addSql('CREATE INDEX IDX_CC3D26C597766307 ON submission_criterion (criterion_id)');
        $this->addSql('ALTER TABLE submission ADD CONSTRAINT FK_DB055AF37E3C61F9 FOREIGN KEY (owner_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE submission ADD CONSTRAINT FK_DB055AF3D19302F8 FOREIGN KEY (assignment_id) REFERENCES assignment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE submission ADD CONSTRAINT FK_DB055AF3EA675D86 FOREIGN KEY (log_id) REFERENCES log (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE submission_criterion ADD CONSTRAINT FK_CC3D26C5E1FD4933 FOREIGN KEY (submission_id) REFERENCES submission (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE submission_criterion ADD CONSTRAINT FK_CC3D26C597766307 FOREIGN KEY (criterion_id) REFERENCES criterion (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE resource_file ADD submission_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE resource_file ADD CONSTRAINT FK_83BF96AAE1FD4933 FOREIGN KEY (submission_id) REFERENCES submission (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_83BF96AAE1FD4933 ON resource_file (submission_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE resource_file DROP CONSTRAINT FK_83BF96AAE1FD4933');
        $this->addSql('ALTER TABLE submission_criterion DROP CONSTRAINT FK_CC3D26C5E1FD4933');
        $this->addSql('DROP SEQUENCE submission_id_seq CASCADE');
        $this->addSql('DROP TABLE submission');
        $this->addSql('DROP TABLE submission_criterion');
        $this->addSql('DROP INDEX IDX_83BF96AAE1FD4933');
        $this->addSql('ALTER TABLE resource_file DROP submission_id');
    }
}
