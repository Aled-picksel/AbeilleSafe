<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210304095320 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alert ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE alert ADD CONSTRAINT FK_17FD46C17E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_17FD46C17E3C61F9 ON alert (owner_id)');
        $this->addSql('ALTER TABLE hive ADD owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hive ADD CONSTRAINT FK_DC6DBBF87E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DC6DBBF87E3C61F9 ON hive (owner_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alert DROP FOREIGN KEY FK_17FD46C17E3C61F9');
        $this->addSql('DROP INDEX IDX_17FD46C17E3C61F9 ON alert');
        $this->addSql('ALTER TABLE alert DROP owner_id');
        $this->addSql('ALTER TABLE hive DROP FOREIGN KEY FK_DC6DBBF87E3C61F9');
        $this->addSql('DROP INDEX IDX_DC6DBBF87E3C61F9 ON hive');
        $this->addSql('ALTER TABLE hive DROP owner_id');
    }
}
