<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200809040632 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE assignment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE assignment_person_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE criterion_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE descriptor_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE log_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE person_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE resource_file_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE set_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE subject_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE topic_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE assignment (id INT NOT NULL, subject_id INT DEFAULT NULL, topic_id INT DEFAULT NULL, set_id INT DEFAULT NULL, teacher_id INT DEFAULT NULL, person_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, state VARCHAR(100) DEFAULT NULL, note VARCHAR(255) DEFAULT NULL, updatetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_30C544BA23EDC87 ON assignment (subject_id)');
        $this->addSql('CREATE INDEX IDX_30C544BA1F55203D ON assignment (topic_id)');
        $this->addSql('CREATE INDEX IDX_30C544BA10FB0D18 ON assignment (set_id)');
        $this->addSql('CREATE INDEX IDX_30C544BA41807E1D ON assignment (teacher_id)');
        $this->addSql('CREATE INDEX IDX_30C544BA217BBB47 ON assignment (person_id)');
        $this->addSql('CREATE TABLE assignment_criteria (assignment_id INT NOT NULL, criterion_id INT NOT NULL, PRIMARY KEY(assignment_id, criterion_id))');
        $this->addSql('CREATE INDEX IDX_721DEF74D19302F8 ON assignment_criteria (assignment_id)');
        $this->addSql('CREATE INDEX IDX_721DEF7497766307 ON assignment_criteria (criterion_id)');
        $this->addSql('CREATE TABLE assignment_person (id INT NOT NULL, person_id INT DEFAULT NULL, assignment_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CFDF16B217BBB47 ON assignment_person (person_id)');
        $this->addSql('CREATE INDEX IDX_CFDF16BD19302F8 ON assignment_person (assignment_id)');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, person_id INT DEFAULT NULL, student_id INT DEFAULT NULL, assignment_id INT DEFAULT NULL, text TEXT NOT NULL, type VARCHAR(100) NOT NULL, updatetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526C217BBB47 ON comment (person_id)');
        $this->addSql('CREATE INDEX IDX_9474526CCB944F1A ON comment (student_id)');
        $this->addSql('CREATE INDEX IDX_9474526CD19302F8 ON comment (assignment_id)');
        $this->addSql('CREATE TABLE criterion (id INT NOT NULL, person_id INT DEFAULT NULL, log_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7C822271217BBB47 ON criterion (person_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7C822271EA675D86 ON criterion (log_id)');
        $this->addSql('CREATE TABLE descriptor (id INT NOT NULL, person_id INT DEFAULT NULL, log_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, type VARCHAR(100) NOT NULL, weight INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3927602217BBB47 ON descriptor (person_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3927602EA675D86 ON descriptor (log_id)');
        $this->addSql('CREATE TABLE criteria_descriptors (descriptor_id INT NOT NULL, criterion_id INT NOT NULL, PRIMARY KEY(descriptor_id, criterion_id))');
        $this->addSql('CREATE INDEX IDX_4731B8422A13D45 ON criteria_descriptors (descriptor_id)');
        $this->addSql('CREATE INDEX IDX_4731B84297766307 ON criteria_descriptors (criterion_id)');
        $this->addSql('CREATE TABLE log (id INT NOT NULL, person_id INT DEFAULT NULL, operation VARCHAR(255) NOT NULL, timestamp TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, result VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8F3F68C5217BBB47 ON log (person_id)');
        $this->addSql('CREATE TABLE person (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(150) DEFAULT NULL, disabled SMALLINT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_34DCD176E7927C74 ON person (email)');
        $this->addSql('CREATE TABLE people_sets (person_id INT NOT NULL, set_id INT NOT NULL, PRIMARY KEY(person_id, set_id))');
        $this->addSql('CREATE INDEX IDX_96448961217BBB47 ON people_sets (person_id)');
        $this->addSql('CREATE INDEX IDX_9644896110FB0D18 ON people_sets (set_id)');
        $this->addSql('CREATE TABLE resource_file (id INT NOT NULL, owner_id INT NOT NULL, log_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, size INT NOT NULL, type VARCHAR(255) NOT NULL, meta JSON DEFAULT NULL, updatetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_83BF96AA7E3C61F9 ON resource_file (owner_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_83BF96AAEA675D86 ON resource_file (log_id)');
        $this->addSql('CREATE TABLE set (id INT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE subject (id INT NOT NULL, person_id INT DEFAULT NULL, log_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FBCE3E7A5E237E06 ON subject (name)');
        $this->addSql('CREATE INDEX IDX_FBCE3E7A217BBB47 ON subject (person_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FBCE3E7AEA675D86 ON subject (log_id)');
        $this->addSql('CREATE TABLE topic (id INT NOT NULL, person_id INT DEFAULT NULL, log_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9D40DE1B217BBB47 ON topic (person_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9D40DE1BEA675D86 ON topic (log_id)');
        $this->addSql('ALTER TABLE assignment ADD CONSTRAINT FK_30C544BA23EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE assignment ADD CONSTRAINT FK_30C544BA1F55203D FOREIGN KEY (topic_id) REFERENCES topic (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE assignment ADD CONSTRAINT FK_30C544BA10FB0D18 FOREIGN KEY (set_id) REFERENCES set (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE assignment ADD CONSTRAINT FK_30C544BA41807E1D FOREIGN KEY (teacher_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE assignment ADD CONSTRAINT FK_30C544BA217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE assignment_criteria ADD CONSTRAINT FK_721DEF74D19302F8 FOREIGN KEY (assignment_id) REFERENCES assignment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE assignment_criteria ADD CONSTRAINT FK_721DEF7497766307 FOREIGN KEY (criterion_id) REFERENCES criterion (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE assignment_person ADD CONSTRAINT FK_CFDF16B217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE assignment_person ADD CONSTRAINT FK_CFDF16BD19302F8 FOREIGN KEY (assignment_id) REFERENCES assignment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CCB944F1A FOREIGN KEY (student_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CD19302F8 FOREIGN KEY (assignment_id) REFERENCES assignment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE criterion ADD CONSTRAINT FK_7C822271217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE criterion ADD CONSTRAINT FK_7C822271EA675D86 FOREIGN KEY (log_id) REFERENCES log (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE descriptor ADD CONSTRAINT FK_3927602217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE descriptor ADD CONSTRAINT FK_3927602EA675D86 FOREIGN KEY (log_id) REFERENCES log (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE criteria_descriptors ADD CONSTRAINT FK_4731B8422A13D45 FOREIGN KEY (descriptor_id) REFERENCES descriptor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE criteria_descriptors ADD CONSTRAINT FK_4731B84297766307 FOREIGN KEY (criterion_id) REFERENCES criterion (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C5217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE people_sets ADD CONSTRAINT FK_96448961217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE people_sets ADD CONSTRAINT FK_9644896110FB0D18 FOREIGN KEY (set_id) REFERENCES set (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE resource_file ADD CONSTRAINT FK_83BF96AA7E3C61F9 FOREIGN KEY (owner_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE resource_file ADD CONSTRAINT FK_83BF96AAEA675D86 FOREIGN KEY (log_id) REFERENCES log (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT FK_FBCE3E7A217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT FK_FBCE3E7AEA675D86 FOREIGN KEY (log_id) REFERENCES log (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1B217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1BEA675D86 FOREIGN KEY (log_id) REFERENCES log (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE assignment_criteria DROP CONSTRAINT FK_721DEF74D19302F8');
        $this->addSql('ALTER TABLE assignment_person DROP CONSTRAINT FK_CFDF16BD19302F8');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CD19302F8');
        $this->addSql('ALTER TABLE assignment_criteria DROP CONSTRAINT FK_721DEF7497766307');
        $this->addSql('ALTER TABLE criteria_descriptors DROP CONSTRAINT FK_4731B84297766307');
        $this->addSql('ALTER TABLE criteria_descriptors DROP CONSTRAINT FK_4731B8422A13D45');
        $this->addSql('ALTER TABLE criterion DROP CONSTRAINT FK_7C822271EA675D86');
        $this->addSql('ALTER TABLE descriptor DROP CONSTRAINT FK_3927602EA675D86');
        $this->addSql('ALTER TABLE resource_file DROP CONSTRAINT FK_83BF96AAEA675D86');
        $this->addSql('ALTER TABLE subject DROP CONSTRAINT FK_FBCE3E7AEA675D86');
        $this->addSql('ALTER TABLE topic DROP CONSTRAINT FK_9D40DE1BEA675D86');
        $this->addSql('ALTER TABLE assignment DROP CONSTRAINT FK_30C544BA41807E1D');
        $this->addSql('ALTER TABLE assignment DROP CONSTRAINT FK_30C544BA217BBB47');
        $this->addSql('ALTER TABLE assignment_person DROP CONSTRAINT FK_CFDF16B217BBB47');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C217BBB47');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CCB944F1A');
        $this->addSql('ALTER TABLE criterion DROP CONSTRAINT FK_7C822271217BBB47');
        $this->addSql('ALTER TABLE descriptor DROP CONSTRAINT FK_3927602217BBB47');
        $this->addSql('ALTER TABLE log DROP CONSTRAINT FK_8F3F68C5217BBB47');
        $this->addSql('ALTER TABLE people_sets DROP CONSTRAINT FK_96448961217BBB47');
        $this->addSql('ALTER TABLE resource_file DROP CONSTRAINT FK_83BF96AA7E3C61F9');
        $this->addSql('ALTER TABLE subject DROP CONSTRAINT FK_FBCE3E7A217BBB47');
        $this->addSql('ALTER TABLE topic DROP CONSTRAINT FK_9D40DE1B217BBB47');
        $this->addSql('ALTER TABLE assignment DROP CONSTRAINT FK_30C544BA10FB0D18');
        $this->addSql('ALTER TABLE people_sets DROP CONSTRAINT FK_9644896110FB0D18');
        $this->addSql('ALTER TABLE assignment DROP CONSTRAINT FK_30C544BA23EDC87');
        $this->addSql('ALTER TABLE assignment DROP CONSTRAINT FK_30C544BA1F55203D');
        $this->addSql('DROP SEQUENCE assignment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE assignment_person_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE criterion_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE descriptor_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE log_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE person_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE resource_file_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE set_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE subject_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE topic_id_seq CASCADE');
        $this->addSql('DROP TABLE assignment');
        $this->addSql('DROP TABLE assignment_criteria');
        $this->addSql('DROP TABLE assignment_person');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE criterion');
        $this->addSql('DROP TABLE descriptor');
        $this->addSql('DROP TABLE criteria_descriptors');
        $this->addSql('DROP TABLE log');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE people_sets');
        $this->addSql('DROP TABLE resource_file');
        $this->addSql('DROP TABLE set');
        $this->addSql('DROP TABLE subject');
        $this->addSql('DROP TABLE topic');
    }
}
