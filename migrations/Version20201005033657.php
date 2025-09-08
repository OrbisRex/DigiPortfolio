<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201005033657 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feedback DROP CONSTRAINT fk_d22944587e3c61f9');
        $this->addSql('DROP INDEX uniq_d22944587e3c61f9');
        $this->addSql('ALTER TABLE feedback RENAME COLUMN owner_id TO person_id');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D2294458217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D2294458217BBB47 ON feedback (person_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE feedback DROP CONSTRAINT FK_D2294458217BBB47');
        $this->addSql('DROP INDEX IDX_D2294458217BBB47');
        $this->addSql('ALTER TABLE feedback RENAME COLUMN person_id TO owner_id');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT fk_d22944587e3c61f9 FOREIGN KEY (owner_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_d22944587e3c61f9 ON feedback (owner_id)');
    }
}
