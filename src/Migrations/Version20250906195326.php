<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250906195326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE person_subject (person_id INT NOT NULL, subject_id INT NOT NULL, PRIMARY KEY(person_id, subject_id))');
        $this->addSql('CREATE INDEX IDX_D9508DF3217BBB47 ON person_subject (person_id)');
        $this->addSql('CREATE INDEX IDX_D9508DF323EDC87 ON person_subject (subject_id)');
        $this->addSql('CREATE TABLE person_submission (person_id INT NOT NULL, submission_id INT NOT NULL, PRIMARY KEY(person_id, submission_id))');
        $this->addSql('CREATE INDEX IDX_EDBE39E6217BBB47 ON person_submission (person_id)');
        $this->addSql('CREATE INDEX IDX_EDBE39E6E1FD4933 ON person_submission (submission_id)');
        $this->addSql('CREATE TABLE submission_person (submission_id INT NOT NULL, person_id INT NOT NULL, PRIMARY KEY(submission_id, person_id))');
        $this->addSql('CREATE INDEX IDX_CD98CC8E1FD4933 ON submission_person (submission_id)');
        $this->addSql('CREATE INDEX IDX_CD98CC8217BBB47 ON submission_person (person_id)');
        $this->addSql('ALTER TABLE person_subject ADD CONSTRAINT FK_D9508DF3217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE person_subject ADD CONSTRAINT FK_D9508DF323EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE person_submission ADD CONSTRAINT FK_EDBE39E6217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE person_submission ADD CONSTRAINT FK_EDBE39E6E1FD4933 FOREIGN KEY (submission_id) REFERENCES submission (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE submission_person ADD CONSTRAINT FK_CD98CC8E1FD4933 FOREIGN KEY (submission_id) REFERENCES submission (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE submission_person ADD CONSTRAINT FK_CD98CC8217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subject DROP CONSTRAINT fk_fbce3e7a217bbb47');
        $this->addSql('DROP INDEX idx_fbce3e7a217bbb47');
        $this->addSql('ALTER TABLE subject DROP person_id');
        $this->addSql('ALTER TABLE submission DROP CONSTRAINT fk_db055af3217bbb47');
        $this->addSql('DROP INDEX idx_db055af3217bbb47');
        $this->addSql('ALTER TABLE submission DROP person_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE person_subject');
        $this->addSql('DROP TABLE person_submission');
        $this->addSql('DROP TABLE submission_person');
        $this->addSql('ALTER TABLE subject ADD person_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT fk_fbce3e7a217bbb47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_fbce3e7a217bbb47 ON subject (person_id)');
        $this->addSql('ALTER TABLE submission ADD person_id INT NOT NULL');
        $this->addSql('ALTER TABLE submission ADD CONSTRAINT fk_db055af3217bbb47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_db055af3217bbb47 ON submission (person_id)');
    }
}
