<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210225142915 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hive (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, creation_date DATE NOT NULL, hausse_count SMALLINT NOT NULL, api_url VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, hive_reported_id INT NOT NULL, date_time DATETIME NOT NULL, inside_temperature DOUBLE PRECISION DEFAULT NULL, inside_humidity DOUBLE PRECISION DEFAULT NULL, outside_temperature DOUBLE PRECISION DEFAULT NULL, outside_humidity DOUBLE PRECISION DEFAULT NULL, weight DOUBLE PRECISION DEFAULT NULL, atmospheric_pressure DOUBLE PRECISION DEFAULT NULL, INDEX IDX_C42F778422010088 (hive_reported_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F778422010088 FOREIGN KEY (hive_reported_id) REFERENCES hive (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F778422010088');
        $this->addSql('DROP TABLE hive');
        $this->addSql('DROP TABLE report');
    }
}
