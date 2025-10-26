<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251004144634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE set_person DROP CONSTRAINT set_person_pkey');
        $this->addSql('ALTER TABLE set_person ADD set_id INT NOT NULL');
        $this->addSql('ALTER TABLE set_person ADD CONSTRAINT FK_9099E4E710FB0D18 FOREIGN KEY (set_id) REFERENCES set (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE set_person ADD CONSTRAINT FK_9099E4E7217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9099E4E710FB0D18 ON set_person (set_id)');
        $this->addSql('CREATE INDEX IDX_9099E4E7217BBB47 ON set_person (person_id)');
        $this->addSql('ALTER TABLE set_person ADD PRIMARY KEY (set_id, person_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE set_person DROP CONSTRAINT FK_9099E4E710FB0D18');
        $this->addSql('ALTER TABLE set_person DROP CONSTRAINT FK_9099E4E7217BBB47');
        $this->addSql('DROP INDEX IDX_9099E4E710FB0D18');
        $this->addSql('DROP INDEX IDX_9099E4E7217BBB47');
        $this->addSql('DROP INDEX set_person_pkey');
        $this->addSql('ALTER TABLE set_person DROP set_id');
        $this->addSql('ALTER TABLE set_person ADD PRIMARY KEY (person_id)');
    }
}
