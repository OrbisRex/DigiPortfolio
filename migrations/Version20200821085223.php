<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200821085223 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE submission DROP CONSTRAINT fk_db055af37e3c61f9');
        $this->addSql('DROP INDEX uniq_db055af37e3c61f9');
        $this->addSql('ALTER TABLE submission RENAME COLUMN owner_id TO person_id');
        $this->addSql('ALTER TABLE submission ADD CONSTRAINT FK_DB055AF3217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_DB055AF3217BBB47 ON submission (person_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE submission DROP CONSTRAINT FK_DB055AF3217BBB47');
        $this->addSql('DROP INDEX IDX_DB055AF3217BBB47');
        $this->addSql('ALTER TABLE submission RENAME COLUMN person_id TO owner_id');
        $this->addSql('ALTER TABLE submission ADD CONSTRAINT fk_db055af37e3c61f9 FOREIGN KEY (owner_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_db055af37e3c61f9 ON submission (owner_id)');
    }
}
