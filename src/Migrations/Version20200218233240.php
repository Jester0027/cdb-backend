<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200218233240 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE animal (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, animal_category_id INTEGER NOT NULL, refuge_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, race VARCHAR(255) NOT NULL, height INTEGER NOT NULL, weight INTEGER NOT NULL, age INTEGER NOT NULL, gender BOOLEAN NOT NULL, price INTEGER NOT NULL, attitude CLOB NOT NULL, description CLOB NOT NULL)');
        $this->addSql('CREATE INDEX IDX_6AAB231FB5FC4E5B ON animal (animal_category_id)');
        $this->addSql('CREATE INDEX IDX_6AAB231FAD3404C1 ON animal (refuge_id)');
        $this->addSql('CREATE TABLE animal_category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, event_theme_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, event_date DATETIME NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zip_code INTEGER NOT NULL, coordinates VARCHAR(255) NOT NULL, image_url VARCHAR(255) NOT NULL, description CLOB NOT NULL)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA71FEE9D57 ON event (event_theme_id)');
        $this->addSql('CREATE TABLE event_theme (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description CLOB NOT NULL)');
        $this->addSql('CREATE TABLE picture (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, animal_id INTEGER DEFAULT NULL, url VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_16DB4F898E962C16 ON picture (animal_id)');
        $this->addSql('CREATE TABLE refuge (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zip_code INTEGER NOT NULL, coordinates VARCHAR(255) NOT NULL, description CLOB NOT NULL)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE animal');
        $this->addSql('DROP TABLE animal_category');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_theme');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE refuge');
    }
}
