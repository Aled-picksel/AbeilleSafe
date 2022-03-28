<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210303171643 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alert (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, short_desc VARCHAR(255) NOT NULL, long_desc VARCHAR(255) NOT NULL, emission_date_time DATETIME NOT NULL, is_dismissed TINYINT(1) NOT NULL, INDEX IDX_17FD46C1C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE alert_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, danger SMALLINT NOT NULL, icon VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE alert ADD CONSTRAINT FK_17FD46C1C54C8C93 FOREIGN KEY (type_id) REFERENCES alert_type (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alert DROP FOREIGN KEY FK_17FD46C1C54C8C93');
        $this->addSql('DROP TABLE alert');
        $this->addSql('DROP TABLE alert_type');
    }
}
