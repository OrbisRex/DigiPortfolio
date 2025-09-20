<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250920194608 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE submission_criterion DROP CONSTRAINT fk_cc3d26c597766307');
        $this->addSql('ALTER TABLE submission_criterion DROP CONSTRAINT fk_cc3d26c5e1fd4933');
        $this->addSql('DROP TABLE submission_criterion');
        $this->addSql('ALTER TABLE assignment ALTER updatetime TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING updatetime::timestamp(0) without time zone');
        $this->addSql('COMMENT ON COLUMN assignment.updatetime IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE comment ALTER createtime TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING createtime::timestamp(0) without time zone');
        $this->addSql('COMMENT ON COLUMN comment.createtime IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE descriptor ADD comment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE descriptor ADD CONSTRAINT FK_3927602F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3927602F8697D13 ON descriptor (comment_id)');
        $this->addSql('ALTER TABLE feedback ALTER createtime TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING createtime::timestamp(0) without time zone');
        $this->addSql('ALTER TABLE feedback ALTER updatetime TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING updatetime::timestamp(0) without time zone');
        $this->addSql('COMMENT ON COLUMN feedback.createtime IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN feedback.updatetime IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE log ALTER "timestamp" TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING "timestamp"::timestamp(0) without time zone');
        $this->addSql('COMMENT ON COLUMN log.timestamp IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE resource_file ALTER updatetime TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING updatetime::timestamp(0) without time zone');
        $this->addSql('COMMENT ON COLUMN resource_file.updatetime IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE submission ALTER updatetime TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING updatetime::timestamp(0) without time zone');
        $this->addSql('ALTER TABLE submission ALTER createtime TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING createtime::timestamp(0) without time zone');
        $this->addSql('COMMENT ON COLUMN submission.updatetime IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN submission.createtime IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE submission_criterion (submission_id INT NOT NULL, criterion_id INT NOT NULL, PRIMARY KEY(submission_id, criterion_id))');
        $this->addSql('CREATE INDEX idx_cc3d26c597766307 ON submission_criterion (criterion_id)');
        $this->addSql('CREATE INDEX idx_cc3d26c5e1fd4933 ON submission_criterion (submission_id)');
        $this->addSql('ALTER TABLE submission_criterion ADD CONSTRAINT fk_cc3d26c597766307 FOREIGN KEY (criterion_id) REFERENCES criterion (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE submission_criterion ADD CONSTRAINT fk_cc3d26c5e1fd4933 FOREIGN KEY (submission_id) REFERENCES submission (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE assignment ALTER updatetime TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN assignment.updatetime IS NULL');
        $this->addSql('ALTER TABLE feedback ALTER createtime TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE feedback ALTER updatetime TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN feedback.createtime IS NULL');
        $this->addSql('COMMENT ON COLUMN feedback.updatetime IS NULL');
        $this->addSql('ALTER TABLE resource_file ALTER updatetime TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN resource_file.updatetime IS NULL');
        $this->addSql('ALTER TABLE descriptor DROP CONSTRAINT FK_3927602F8697D13');
        $this->addSql('DROP INDEX UNIQ_3927602F8697D13');
        $this->addSql('ALTER TABLE descriptor DROP comment_id');
        $this->addSql('ALTER TABLE submission ALTER updatetime TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE submission ALTER createtime TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN submission.updatetime IS NULL');
        $this->addSql('COMMENT ON COLUMN submission.createtime IS NULL');
        $this->addSql('ALTER TABLE log ALTER timestamp TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN log."timestamp" IS NULL');
        $this->addSql('ALTER TABLE comment ALTER createtime TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN comment.createtime IS NULL');
    }
}
