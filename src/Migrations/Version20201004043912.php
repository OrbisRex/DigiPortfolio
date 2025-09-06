<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201004043912 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE feedback_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE feedback (id INT NOT NULL, owner_id INT NOT NULL, submission_id INT NOT NULL, log_id INT DEFAULT NULL, note TEXT DEFAULT NULL, version INT NOT NULL, createtime TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updatetime TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D22944587E3C61F9 ON feedback (owner_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D2294458E1FD4933 ON feedback (submission_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D2294458EA675D86 ON feedback (log_id)');
        $this->addSql('CREATE TABLE feedback_descriptor (feedback_id INT NOT NULL, descriptor_id INT NOT NULL, PRIMARY KEY(feedback_id, descriptor_id))');
        $this->addSql('CREATE INDEX IDX_5F9F5858D249A887 ON feedback_descriptor (feedback_id)');
        $this->addSql('CREATE INDEX IDX_5F9F58582A13D45 ON feedback_descriptor (descriptor_id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944587E3C61F9 FOREIGN KEY (owner_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458E1FD4933 FOREIGN KEY (submission_id) REFERENCES submission (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458EA675D86 FOREIGN KEY (log_id) REFERENCES log (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE feedback_descriptor ADD CONSTRAINT FK_5F9F5858D249A887 FOREIGN KEY (feedback_id) REFERENCES feedback (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE feedback_descriptor ADD CONSTRAINT FK_5F9F58582A13D45 FOREIGN KEY (descriptor_id) REFERENCES descriptor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE feedback_descriptor DROP CONSTRAINT FK_5F9F5858D249A887');
        $this->addSql('DROP SEQUENCE feedback_id_seq CASCADE');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE feedback_descriptor');
    }
}
