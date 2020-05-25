<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200525123736 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_6AAB231FB5FC4E5B');
        $this->addSql('DROP INDEX IDX_6AAB231FAD3404C1');
        $this->addSql('CREATE TEMPORARY TABLE __temp__animal AS SELECT id, animal_category_id, refuge_id, name, race, height, weight, age, gender, attitude, description FROM animal');
        $this->addSql('DROP TABLE animal');
        $this->addSql('CREATE TABLE animal (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , animal_category_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , refuge_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , name VARCHAR(255) NOT NULL COLLATE BINARY, race VARCHAR(255) NOT NULL COLLATE BINARY, height INTEGER NOT NULL, weight INTEGER NOT NULL, age INTEGER NOT NULL, gender BOOLEAN NOT NULL, attitude CLOB NOT NULL COLLATE BINARY, description CLOB NOT NULL COLLATE BINARY, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_6AAB231FB5FC4E5B FOREIGN KEY (animal_category_id) REFERENCES animal_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6AAB231FAD3404C1 FOREIGN KEY (refuge_id) REFERENCES refuge (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO animal (id, animal_category_id, refuge_id, name, race, height, weight, age, gender, attitude, description) SELECT id, animal_category_id, refuge_id, name, race, height, weight, age, gender, attitude, description FROM __temp__animal');
        $this->addSql('DROP TABLE __temp__animal');
        $this->addSql('CREATE INDEX IDX_6AAB231FB5FC4E5B ON animal (animal_category_id)');
        $this->addSql('CREATE INDEX IDX_6AAB231FAD3404C1 ON animal (refuge_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6AAB231F989D9B62 ON animal (slug)');
        $this->addSql('DROP INDEX IDX_3BAE0AA71FEE9D57');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, event_theme_id, title, event_date, address, city, zip_code, coordinates, image_url, updated_at, description FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , event_theme_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , title VARCHAR(255) NOT NULL COLLATE BINARY, event_date DATETIME NOT NULL, address VARCHAR(255) NOT NULL COLLATE BINARY, city VARCHAR(255) NOT NULL COLLATE BINARY, zip_code VARCHAR(255) NOT NULL COLLATE BINARY, coordinates VARCHAR(255) NOT NULL COLLATE BINARY, image_url VARCHAR(255) DEFAULT NULL COLLATE BINARY, updated_at DATETIME DEFAULT NULL, description CLOB NOT NULL COLLATE BINARY, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_3BAE0AA71FEE9D57 FOREIGN KEY (event_theme_id) REFERENCES event_theme (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO event (id, event_theme_id, title, event_date, address, city, zip_code, coordinates, image_url, updated_at, description) SELECT id, event_theme_id, title, event_date, address, city, zip_code, coordinates, image_url, updated_at, description FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
        $this->addSql('CREATE INDEX IDX_3BAE0AA71FEE9D57 ON event (event_theme_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3BAE0AA7989D9B62 ON event (slug)');
        $this->addSql('DROP INDEX IDX_16DB4F898E962C16');
        $this->addSql('CREATE TEMPORARY TABLE __temp__picture AS SELECT id, animal_id, url FROM picture');
        $this->addSql('DROP TABLE picture');
        $this->addSql('CREATE TABLE picture (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , animal_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , url VARCHAR(255) NOT NULL COLLATE BINARY, PRIMARY KEY(id), CONSTRAINT FK_16DB4F898E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO picture (id, animal_id, url) SELECT id, animal_id, url FROM __temp__picture');
        $this->addSql('DROP TABLE __temp__picture');
        $this->addSql('CREATE INDEX IDX_16DB4F898E962C16 ON picture (animal_id)');
        $this->addSql('DROP INDEX IDX_98BB84BAA76ED395');
        $this->addSql('DROP INDEX IDX_98BB84BAAD3404C1');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_refuge AS SELECT user_id, refuge_id FROM user_refuge');
        $this->addSql('DROP TABLE user_refuge');
        $this->addSql('CREATE TABLE user_refuge (user_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , refuge_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , PRIMARY KEY(user_id, refuge_id), CONSTRAINT FK_98BB84BAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_98BB84BAAD3404C1 FOREIGN KEY (refuge_id) REFERENCES refuge (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user_refuge (user_id, refuge_id) SELECT user_id, refuge_id FROM __temp__user_refuge');
        $this->addSql('DROP TABLE __temp__user_refuge');
        $this->addSql('CREATE INDEX IDX_98BB84BAA76ED395 ON user_refuge (user_id)');
        $this->addSql('CREATE INDEX IDX_98BB84BAAD3404C1 ON user_refuge (refuge_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX UNIQ_6AAB231F989D9B62');
        $this->addSql('DROP INDEX IDX_6AAB231FB5FC4E5B');
        $this->addSql('DROP INDEX IDX_6AAB231FAD3404C1');
        $this->addSql('CREATE TEMPORARY TABLE __temp__animal AS SELECT id, animal_category_id, refuge_id, name, race, height, weight, age, gender, attitude, description FROM animal');
        $this->addSql('DROP TABLE animal');
        $this->addSql('CREATE TABLE animal (id CHAR(36) NOT NULL --(DC2Type:guid)
        , animal_category_id CHAR(36) NOT NULL --(DC2Type:guid)
        , refuge_id CHAR(36) NOT NULL --(DC2Type:guid)
        , name VARCHAR(255) NOT NULL, race VARCHAR(255) NOT NULL, height INTEGER NOT NULL, weight INTEGER NOT NULL, age INTEGER NOT NULL, gender BOOLEAN NOT NULL, attitude CLOB NOT NULL, description CLOB NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO animal (id, animal_category_id, refuge_id, name, race, height, weight, age, gender, attitude, description) SELECT id, animal_category_id, refuge_id, name, race, height, weight, age, gender, attitude, description FROM __temp__animal');
        $this->addSql('DROP TABLE __temp__animal');
        $this->addSql('CREATE INDEX IDX_6AAB231FB5FC4E5B ON animal (animal_category_id)');
        $this->addSql('CREATE INDEX IDX_6AAB231FAD3404C1 ON animal (refuge_id)');
        $this->addSql('DROP INDEX UNIQ_3BAE0AA7989D9B62');
        $this->addSql('DROP INDEX IDX_3BAE0AA71FEE9D57');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, event_theme_id, title, event_date, address, city, zip_code, coordinates, image_url, updated_at, description FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id CHAR(36) NOT NULL --(DC2Type:guid)
        , title VARCHAR(255) NOT NULL, event_date DATETIME NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zip_code VARCHAR(255) NOT NULL, coordinates VARCHAR(255) NOT NULL, image_url VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, description CLOB NOT NULL, event_theme_id CHAR(36) DEFAULT \'NULL --(DC2Type:guid)\' COLLATE BINARY --(DC2Type:guid)
        , PRIMARY KEY(id))');
        $this->addSql('INSERT INTO event (id, event_theme_id, title, event_date, address, city, zip_code, coordinates, image_url, updated_at, description) SELECT id, event_theme_id, title, event_date, address, city, zip_code, coordinates, image_url, updated_at, description FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
        $this->addSql('CREATE INDEX IDX_3BAE0AA71FEE9D57 ON event (event_theme_id)');
        $this->addSql('DROP INDEX IDX_16DB4F898E962C16');
        $this->addSql('CREATE TEMPORARY TABLE __temp__picture AS SELECT id, animal_id, url FROM picture');
        $this->addSql('DROP TABLE picture');
        $this->addSql('CREATE TABLE picture (id CHAR(36) NOT NULL --(DC2Type:guid)
        , url VARCHAR(255) NOT NULL, animal_id CHAR(36) DEFAULT \'NULL --(DC2Type:guid)\' COLLATE BINARY --(DC2Type:guid)
        , PRIMARY KEY(id))');
        $this->addSql('INSERT INTO picture (id, animal_id, url) SELECT id, animal_id, url FROM __temp__picture');
        $this->addSql('DROP TABLE __temp__picture');
        $this->addSql('CREATE INDEX IDX_16DB4F898E962C16 ON picture (animal_id)');
        $this->addSql('DROP INDEX IDX_98BB84BAA76ED395');
        $this->addSql('DROP INDEX IDX_98BB84BAAD3404C1');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_refuge AS SELECT user_id, refuge_id FROM user_refuge');
        $this->addSql('DROP TABLE user_refuge');
        $this->addSql('CREATE TABLE user_refuge (user_id CHAR(36) NOT NULL --(DC2Type:guid)
        , refuge_id CHAR(36) NOT NULL --(DC2Type:guid)
        , PRIMARY KEY(user_id, refuge_id))');
        $this->addSql('INSERT INTO user_refuge (user_id, refuge_id) SELECT user_id, refuge_id FROM __temp__user_refuge');
        $this->addSql('DROP TABLE __temp__user_refuge');
        $this->addSql('CREATE INDEX IDX_98BB84BAA76ED395 ON user_refuge (user_id)');
        $this->addSql('CREATE INDEX IDX_98BB84BAAD3404C1 ON user_refuge (refuge_id)');
    }
}
