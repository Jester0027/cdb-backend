<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200524155428 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_6AAB231FAD3404C1');
        $this->addSql('DROP INDEX IDX_6AAB231FB5FC4E5B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__animal AS SELECT id, name, race, height, weight, age, gender, attitude, description, animal_category_id, refuge_id FROM animal');
        $this->addSql('DROP TABLE animal');
        $this->addSql('CREATE TABLE animal (id CHAR(36) NOT NULL --(DC2Type:guid)
        , animal_category_id CHAR(36) NOT NULL --(DC2Type:guid)
        , refuge_id CHAR(36) NOT NULL --(DC2Type:guid)
        , name VARCHAR(255) NOT NULL COLLATE BINARY, race VARCHAR(255) NOT NULL COLLATE BINARY, height INTEGER NOT NULL, weight INTEGER NOT NULL, age INTEGER NOT NULL, gender BOOLEAN NOT NULL, attitude CLOB NOT NULL COLLATE BINARY, description CLOB NOT NULL COLLATE BINARY, PRIMARY KEY(id), CONSTRAINT FK_6AAB231FB5FC4E5B FOREIGN KEY (animal_category_id) REFERENCES animal_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6AAB231FAD3404C1 FOREIGN KEY (refuge_id) REFERENCES refuge (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO animal (id, name, race, height, weight, age, gender, attitude, description, animal_category_id, refuge_id) SELECT id, name, race, height, weight, age, gender, attitude, description, animal_category_id, refuge_id FROM __temp__animal');
        $this->addSql('DROP TABLE __temp__animal');
        $this->addSql('CREATE INDEX IDX_6AAB231FAD3404C1 ON animal (refuge_id)');
        $this->addSql('CREATE INDEX IDX_6AAB231FB5FC4E5B ON animal (animal_category_id)');
        $this->addSql('DROP INDEX UNIQ_C0CF1F1C989D9B62');
        $this->addSql('CREATE TEMPORARY TABLE __temp__animal_category AS SELECT id, name, slug FROM animal_category');
        $this->addSql('DROP TABLE animal_category');
        $this->addSql('CREATE TABLE animal_category (id CHAR(36) NOT NULL --(DC2Type:guid)
        , name VARCHAR(255) NOT NULL COLLATE BINARY, slug VARCHAR(255) NOT NULL COLLATE BINARY, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO animal_category (id, name, slug) SELECT id, name, slug FROM __temp__animal_category');
        $this->addSql('DROP TABLE __temp__animal_category');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C0CF1F1C989D9B62 ON animal_category (slug)');
        $this->addSql('DROP INDEX IDX_3BAE0AA71FEE9D57');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, event_theme_id, title, event_date, address, city, zip_code, coordinates, image_url, updated_at, description FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id CHAR(36) NOT NULL --(DC2Type:guid)
        , event_theme_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , title VARCHAR(255) NOT NULL COLLATE BINARY, event_date DATETIME NOT NULL, address VARCHAR(255) NOT NULL COLLATE BINARY, city VARCHAR(255) NOT NULL COLLATE BINARY, zip_code VARCHAR(255) NOT NULL COLLATE BINARY, coordinates VARCHAR(255) NOT NULL COLLATE BINARY, image_url VARCHAR(255) DEFAULT NULL COLLATE BINARY, updated_at DATETIME DEFAULT NULL, description CLOB NOT NULL COLLATE BINARY, PRIMARY KEY(id), CONSTRAINT FK_3BAE0AA71FEE9D57 FOREIGN KEY (event_theme_id) REFERENCES event_theme (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO event (id, event_theme_id, title, event_date, address, city, zip_code, coordinates, image_url, updated_at, description) SELECT id, event_theme_id, title, event_date, address, city, zip_code, coordinates, image_url, updated_at, description FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
        $this->addSql('CREATE INDEX IDX_3BAE0AA71FEE9D57 ON event (event_theme_id)');
        $this->addSql('DROP INDEX UNIQ_D66E5624989D9B62');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event_theme AS SELECT id, name, slug, description FROM event_theme');
        $this->addSql('DROP TABLE event_theme');
        $this->addSql('CREATE TABLE event_theme (id CHAR(36) NOT NULL --(DC2Type:guid)
        , name VARCHAR(255) NOT NULL COLLATE BINARY, slug VARCHAR(255) NOT NULL COLLATE BINARY, description CLOB NOT NULL COLLATE BINARY, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO event_theme (id, name, slug, description) SELECT id, name, slug, description FROM __temp__event_theme');
        $this->addSql('DROP TABLE __temp__event_theme');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D66E5624989D9B62 ON event_theme (slug)');
        $this->addSql('DROP INDEX IDX_16DB4F898E962C16');
        $this->addSql('CREATE TEMPORARY TABLE __temp__picture AS SELECT id, animal_id, url FROM picture');
        $this->addSql('DROP TABLE picture');
        $this->addSql('CREATE TABLE picture (id CHAR(36) NOT NULL --(DC2Type:guid)
        , animal_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , url VARCHAR(255) NOT NULL COLLATE BINARY, PRIMARY KEY(id), CONSTRAINT FK_16DB4F898E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO picture (id, animal_id, url) SELECT id, animal_id, url FROM __temp__picture');
        $this->addSql('DROP TABLE __temp__picture');
        $this->addSql('CREATE INDEX IDX_16DB4F898E962C16 ON picture (animal_id)');
        $this->addSql('DROP INDEX UNIQ_FDE99F87989D9B62');
        $this->addSql('CREATE TEMPORARY TABLE __temp__refuge AS SELECT id, name, slug, address, city, zip_code, coordinates, description FROM refuge');
        $this->addSql('DROP TABLE refuge');
        $this->addSql('CREATE TABLE refuge (id CHAR(36) NOT NULL --(DC2Type:guid)
        , name VARCHAR(255) NOT NULL COLLATE BINARY, slug VARCHAR(255) NOT NULL COLLATE BINARY, address VARCHAR(255) NOT NULL COLLATE BINARY, city VARCHAR(255) NOT NULL COLLATE BINARY, zip_code VARCHAR(255) NOT NULL COLLATE BINARY, coordinates VARCHAR(255) NOT NULL COLLATE BINARY, description CLOB NOT NULL COLLATE BINARY, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO refuge (id, name, slug, address, city, zip_code, coordinates, description) SELECT id, name, slug, address, city, zip_code, coordinates, description FROM __temp__refuge');
        $this->addSql('DROP TABLE __temp__refuge');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FDE99F87989D9B62 ON refuge (slug)');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id CHAR(36) NOT NULL --(DC2Type:guid)
        , email VARCHAR(180) NOT NULL COLLATE BINARY, roles CLOB NOT NULL COLLATE BINARY --(DC2Type:json)
        , password VARCHAR(255) NOT NULL COLLATE BINARY, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO user (id, email, roles, password) SELECT id, email, roles, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('DROP INDEX IDX_98BB84BAAD3404C1');
        $this->addSql('DROP INDEX IDX_98BB84BAA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_refuge AS SELECT user_id, refuge_id FROM user_refuge');
        $this->addSql('DROP TABLE user_refuge');
        $this->addSql('CREATE TABLE user_refuge (user_id CHAR(36) NOT NULL --(DC2Type:guid)
        , refuge_id CHAR(36) NOT NULL --(DC2Type:guid)
        , PRIMARY KEY(user_id, refuge_id), CONSTRAINT FK_98BB84BAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_98BB84BAAD3404C1 FOREIGN KEY (refuge_id) REFERENCES refuge (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user_refuge (user_id, refuge_id) SELECT user_id, refuge_id FROM __temp__user_refuge');
        $this->addSql('DROP TABLE __temp__user_refuge');
        $this->addSql('CREATE INDEX IDX_98BB84BAAD3404C1 ON user_refuge (refuge_id)');
        $this->addSql('CREATE INDEX IDX_98BB84BAA76ED395 ON user_refuge (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_6AAB231FB5FC4E5B');
        $this->addSql('DROP INDEX IDX_6AAB231FAD3404C1');
        $this->addSql('CREATE TEMPORARY TABLE __temp__animal AS SELECT id, animal_category_id, refuge_id, name, race, height, weight, age, gender, attitude, description FROM animal');
        $this->addSql('DROP TABLE animal');
        $this->addSql('CREATE TABLE animal (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, race VARCHAR(255) NOT NULL, height INTEGER NOT NULL, weight INTEGER NOT NULL, age INTEGER NOT NULL, gender BOOLEAN NOT NULL, attitude CLOB NOT NULL, description CLOB NOT NULL, animal_category_id INTEGER DEFAULT NULL, refuge_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO animal (id, animal_category_id, refuge_id, name, race, height, weight, age, gender, attitude, description) SELECT id, animal_category_id, refuge_id, name, race, height, weight, age, gender, attitude, description FROM __temp__animal');
        $this->addSql('DROP TABLE __temp__animal');
        $this->addSql('CREATE INDEX IDX_6AAB231FB5FC4E5B ON animal (animal_category_id)');
        $this->addSql('CREATE INDEX IDX_6AAB231FAD3404C1 ON animal (refuge_id)');
        $this->addSql('DROP INDEX UNIQ_C0CF1F1C989D9B62');
        $this->addSql('CREATE TEMPORARY TABLE __temp__animal_category AS SELECT id, name, slug FROM animal_category');
        $this->addSql('DROP TABLE animal_category');
        $this->addSql('CREATE TABLE animal_category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO animal_category (id, name, slug) SELECT id, name, slug FROM __temp__animal_category');
        $this->addSql('DROP TABLE __temp__animal_category');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C0CF1F1C989D9B62 ON animal_category (slug)');
        $this->addSql('DROP INDEX IDX_3BAE0AA71FEE9D57');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, event_theme_id, title, event_date, address, city, zip_code, coordinates, image_url, updated_at, description FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, event_date DATETIME NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zip_code VARCHAR(255) NOT NULL, coordinates VARCHAR(255) NOT NULL, image_url VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, description CLOB NOT NULL, event_theme_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO event (id, event_theme_id, title, event_date, address, city, zip_code, coordinates, image_url, updated_at, description) SELECT id, event_theme_id, title, event_date, address, city, zip_code, coordinates, image_url, updated_at, description FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
        $this->addSql('CREATE INDEX IDX_3BAE0AA71FEE9D57 ON event (event_theme_id)');
        $this->addSql('DROP INDEX UNIQ_D66E5624989D9B62');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event_theme AS SELECT id, name, slug, description FROM event_theme');
        $this->addSql('DROP TABLE event_theme');
        $this->addSql('CREATE TABLE event_theme (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description CLOB NOT NULL)');
        $this->addSql('INSERT INTO event_theme (id, name, slug, description) SELECT id, name, slug, description FROM __temp__event_theme');
        $this->addSql('DROP TABLE __temp__event_theme');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D66E5624989D9B62 ON event_theme (slug)');
        $this->addSql('DROP INDEX IDX_16DB4F898E962C16');
        $this->addSql('CREATE TEMPORARY TABLE __temp__picture AS SELECT id, animal_id, url FROM picture');
        $this->addSql('DROP TABLE picture');
        $this->addSql('CREATE TABLE picture (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, url VARCHAR(255) NOT NULL, animal_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO picture (id, animal_id, url) SELECT id, animal_id, url FROM __temp__picture');
        $this->addSql('DROP TABLE __temp__picture');
        $this->addSql('CREATE INDEX IDX_16DB4F898E962C16 ON picture (animal_id)');
        $this->addSql('DROP INDEX UNIQ_FDE99F87989D9B62');
        $this->addSql('CREATE TEMPORARY TABLE __temp__refuge AS SELECT id, name, slug, address, city, zip_code, coordinates, description FROM refuge');
        $this->addSql('DROP TABLE refuge');
        $this->addSql('CREATE TABLE refuge (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zip_code VARCHAR(255) NOT NULL, coordinates VARCHAR(255) NOT NULL, description CLOB NOT NULL)');
        $this->addSql('INSERT INTO refuge (id, name, slug, address, city, zip_code, coordinates, description) SELECT id, name, slug, address, city, zip_code, coordinates, description FROM __temp__refuge');
        $this->addSql('DROP TABLE __temp__refuge');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FDE99F87989D9B62 ON refuge (slug)');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, email, roles, password) SELECT id, email, roles, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('DROP INDEX IDX_98BB84BAA76ED395');
        $this->addSql('DROP INDEX IDX_98BB84BAAD3404C1');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_refuge AS SELECT user_id, refuge_id FROM user_refuge');
        $this->addSql('DROP TABLE user_refuge');
        $this->addSql('CREATE TABLE user_refuge (user_id INTEGER NOT NULL, refuge_id INTEGER NOT NULL, PRIMARY KEY(user_id, refuge_id))');
        $this->addSql('INSERT INTO user_refuge (user_id, refuge_id) SELECT user_id, refuge_id FROM __temp__user_refuge');
        $this->addSql('DROP TABLE __temp__user_refuge');
        $this->addSql('CREATE INDEX IDX_98BB84BAA76ED395 ON user_refuge (user_id)');
        $this->addSql('CREATE INDEX IDX_98BB84BAAD3404C1 ON user_refuge (refuge_id)');
    }
}
