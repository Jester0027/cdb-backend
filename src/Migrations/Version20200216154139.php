<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200216154139 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE animal (id INT AUTO_INCREMENT NOT NULL, animal_category_id INT NOT NULL, refuge_id INT NOT NULL, name VARCHAR(255) NOT NULL, race VARCHAR(255) NOT NULL, height INT NOT NULL, weight INT NOT NULL, age INT NOT NULL, gender TINYINT(1) NOT NULL, price INT NOT NULL, attitude LONGTEXT NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_6AAB231FB5FC4E5B (animal_category_id), INDEX IDX_6AAB231FAD3404C1 (refuge_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE animal_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, event_theme_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, event_date DATETIME NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zip_code INT NOT NULL, coordinates VARCHAR(255) NOT NULL, image_url VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_3BAE0AA71FEE9D57 (event_theme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_theme (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, animal_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_16DB4F898E962C16 (animal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refuge (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zip_code INT NOT NULL, coordinates VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231FB5FC4E5B FOREIGN KEY (animal_category_id) REFERENCES animal_category (id)');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231FAD3404C1 FOREIGN KEY (refuge_id) REFERENCES refuge (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA71FEE9D57 FOREIGN KEY (event_theme_id) REFERENCES event_theme (id)');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F898E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F898E962C16');
        $this->addSql('ALTER TABLE animal DROP FOREIGN KEY FK_6AAB231FB5FC4E5B');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA71FEE9D57');
        $this->addSql('ALTER TABLE animal DROP FOREIGN KEY FK_6AAB231FAD3404C1');
        $this->addSql('DROP TABLE animal');
        $this->addSql('DROP TABLE animal_category');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_theme');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE refuge');
    }
}
