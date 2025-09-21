<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250921151425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subject_person DROP CONSTRAINT subject_person_pkey');
        $this->addSql('ALTER TABLE subject_person ADD subject_id INT NOT NULL');
        $this->addSql('ALTER TABLE subject_person ADD CONSTRAINT FK_BCC5185123EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subject_person ADD CONSTRAINT FK_BCC51851217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_BCC5185123EDC87 ON subject_person (subject_id)');
        $this->addSql('CREATE INDEX IDX_BCC51851217BBB47 ON subject_person (person_id)');
        $this->addSql('ALTER TABLE subject_person ADD PRIMARY KEY (subject_id, person_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE subject_person DROP CONSTRAINT FK_BCC5185123EDC87');
        $this->addSql('ALTER TABLE subject_person DROP CONSTRAINT FK_BCC51851217BBB47');
        $this->addSql('DROP INDEX IDX_BCC5185123EDC87');
        $this->addSql('DROP INDEX IDX_BCC51851217BBB47');
        $this->addSql('DROP INDEX subject_person_pkey');
        $this->addSql('ALTER TABLE subject_person DROP subject_id');
        $this->addSql('ALTER TABLE subject_person ADD PRIMARY KEY (person_id)');
    }
}
