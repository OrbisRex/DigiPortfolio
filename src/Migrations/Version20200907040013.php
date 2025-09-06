<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200907040013 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT fk_9474526ccb944f1a');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT fk_9474526cd19302f8');
        $this->addSql('DROP INDEX idx_9474526cd19302f8');
        $this->addSql('DROP INDEX idx_9474526ccb944f1a');
        $this->addSql('ALTER TABLE comment ADD submission_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment DROP student_id');
        $this->addSql('ALTER TABLE comment DROP assignment_id');
        $this->addSql('ALTER TABLE comment RENAME COLUMN updatetime TO createtime');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CE1FD4933 FOREIGN KEY (submission_id) REFERENCES submission (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9474526CE1FD4933 ON comment (submission_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CE1FD4933');
        $this->addSql('DROP INDEX IDX_9474526CE1FD4933');
        $this->addSql('ALTER TABLE comment ADD assignment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment RENAME COLUMN submission_id TO student_id');
        $this->addSql('ALTER TABLE comment RENAME COLUMN createtime TO updatetime');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT fk_9474526ccb944f1a FOREIGN KEY (student_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT fk_9474526cd19302f8 FOREIGN KEY (assignment_id) REFERENCES assignment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_9474526cd19302f8 ON comment (assignment_id)');
        $this->addSql('CREATE INDEX idx_9474526ccb944f1a ON comment (student_id)');
    }
}
