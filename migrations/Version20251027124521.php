<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251027124521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assignment_person DROP CONSTRAINT FK_CFDF16B217BBB47');
        $this->addSql('ALTER TABLE assignment_person DROP CONSTRAINT FK_CFDF16BD19302F8');
        $this->addSql('ALTER TABLE assignment_person ADD CONSTRAINT FK_CFDF16B217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE assignment_person ADD CONSTRAINT FK_CFDF16BD19302F8 FOREIGN KEY (assignment_id) REFERENCES assignment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE assignment_person DROP CONSTRAINT fk_cfdf16b217bbb47');
        $this->addSql('ALTER TABLE assignment_person DROP CONSTRAINT fk_cfdf16bd19302f8');
        $this->addSql('ALTER TABLE assignment_person ADD CONSTRAINT fk_cfdf16b217bbb47 FOREIGN KEY (person_id) REFERENCES assignment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE assignment_person ADD CONSTRAINT fk_cfdf16bd19302f8 FOREIGN KEY (assignment_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
