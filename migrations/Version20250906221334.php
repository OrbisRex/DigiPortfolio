<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250906221334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE set_person (set_id INT NOT NULL, person_id INT NOT NULL, PRIMARY KEY(set_id, person_id))');
        $this->addSql('CREATE INDEX IDX_9099E4E710FB0D18 ON set_person (set_id)');
        $this->addSql('CREATE INDEX IDX_9099E4E7217BBB47 ON set_person (person_id)');
        $this->addSql('CREATE TABLE subject_person (subject_id INT NOT NULL, person_id INT NOT NULL, PRIMARY KEY(subject_id, person_id))');
        $this->addSql('CREATE INDEX IDX_BCC5185123EDC87 ON subject_person (subject_id)');
        $this->addSql('CREATE INDEX IDX_BCC51851217BBB47 ON subject_person (person_id)');
        $this->addSql('CREATE TABLE submission_resource_file (submission_id INT NOT NULL, resource_file_id INT NOT NULL, PRIMARY KEY(submission_id, resource_file_id))');
        $this->addSql('CREATE INDEX IDX_BE9AE640E1FD4933 ON submission_resource_file (submission_id)');
        $this->addSql('CREATE INDEX IDX_BE9AE640CE6B9E84 ON submission_resource_file (resource_file_id)');
        $this->addSql('ALTER TABLE set_person ADD CONSTRAINT FK_9099E4E710FB0D18 FOREIGN KEY (set_id) REFERENCES set (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE set_person ADD CONSTRAINT FK_9099E4E7217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subject_person ADD CONSTRAINT FK_BCC5185123EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subject_person ADD CONSTRAINT FK_BCC51851217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE submission_resource_file ADD CONSTRAINT FK_BE9AE640E1FD4933 FOREIGN KEY (submission_id) REFERENCES submission (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE submission_resource_file ADD CONSTRAINT FK_BE9AE640CE6B9E84 FOREIGN KEY (resource_file_id) REFERENCES resource_file (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE person_subject');
        $this->addSql('DROP TABLE person_set');
        $this->addSql('DROP INDEX idx_8f3f68c5217bbb47');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8F3F68C5217BBB47 ON log (person_id)');
        $this->addSql('ALTER TABLE person ADD log_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176EA675D86 FOREIGN KEY (log_id) REFERENCES log (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_34DCD176EA675D86 ON person (log_id)');
        $this->addSql('ALTER TABLE resource_file DROP CONSTRAINT fk_83bf96aae1fd4933');
        $this->addSql('DROP INDEX idx_83bf96aae1fd4933');
        $this->addSql('ALTER TABLE resource_file DROP submission_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE person_subject (person_id INT NOT NULL, subject_id INT NOT NULL, PRIMARY KEY(person_id, subject_id))');
        $this->addSql('CREATE INDEX idx_d9508df3217bbb47 ON person_subject (person_id)');
        $this->addSql('CREATE INDEX idx_d9508df323edc87 ON person_subject (subject_id)');
        $this->addSql('CREATE TABLE person_set (person_id INT NOT NULL, set_id INT NOT NULL, PRIMARY KEY(person_id, set_id))');
        $this->addSql('CREATE INDEX idx_6559e2c710fb0d18 ON person_set (set_id)');
        $this->addSql('CREATE INDEX idx_6559e2c7217bbb47 ON person_set (person_id)');
        $this->addSql('ALTER TABLE person_subject ADD CONSTRAINT fk_d9508df3217bbb47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE person_subject ADD CONSTRAINT fk_d9508df323edc87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE person_set ADD CONSTRAINT fk_6559e2c7217bbb47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE person_set ADD CONSTRAINT fk_6559e2c710fb0d18 FOREIGN KEY (set_id) REFERENCES set (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE set_person');
        $this->addSql('DROP TABLE subject_person');
        $this->addSql('DROP TABLE submission_resource_file');
        $this->addSql('ALTER TABLE person DROP CONSTRAINT FK_34DCD176EA675D86');
        $this->addSql('DROP INDEX UNIQ_34DCD176EA675D86');
        $this->addSql('ALTER TABLE person DROP log_id');
        $this->addSql('DROP INDEX UNIQ_8F3F68C5217BBB47');
        $this->addSql('CREATE INDEX idx_8f3f68c5217bbb47 ON log (person_id)');
        $this->addSql('ALTER TABLE resource_file ADD submission_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE resource_file ADD CONSTRAINT fk_83bf96aae1fd4933 FOREIGN KEY (submission_id) REFERENCES submission (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_83bf96aae1fd4933 ON resource_file (submission_id)');
    }
}
