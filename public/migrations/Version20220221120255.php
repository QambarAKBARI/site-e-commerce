<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220221120255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_52743D7BBCF5E72D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sous_categorie AS SELECT id, categorie_id, nom_sous_categorie FROM sous_categorie');
        $this->addSql('DROP TABLE sous_categorie');
        $this->addSql('CREATE TABLE sous_categorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categorie_id INTEGER DEFAULT NULL, nom_sous_categorie VARCHAR(100) DEFAULT NULL, CONSTRAINT FK_52743D7BBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sous_categorie (id, categorie_id, nom_sous_categorie) SELECT id, categorie_id, nom_sous_categorie FROM __temp__sous_categorie');
        $this->addSql('DROP TABLE __temp__sous_categorie');
        $this->addSql('CREATE INDEX IDX_52743D7BBCF5E72D ON sous_categorie (categorie_id)');
        $this->addSql('ALTER TABLE user ADD COLUMN nom VARCHAR(100)  NULL');
        $this->addSql('ALTER TABLE user ADD COLUMN prenom VARCHAR(100)  NULL');
        $this->addSql('ALTER TABLE user ADD COLUMN adresse VARCHAR(255)  NULL');
        $this->addSql('ALTER TABLE user ADD COLUMN cp VARCHAR(50)  NULL');
        $this->addSql('ALTER TABLE user ADD COLUMN ville VARCHAR(50)  NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_52743D7BBCF5E72D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sous_categorie AS SELECT id, categorie_id, nom_sous_categorie FROM sous_categorie');
        $this->addSql('DROP TABLE sous_categorie');
        $this->addSql('CREATE TABLE sous_categorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categorie_id INTEGER DEFAULT NULL, nom_sous_categorie VARCHAR(100) DEFAULT NULL)');
        $this->addSql('INSERT INTO sous_categorie (id, categorie_id, nom_sous_categorie) SELECT id, categorie_id, nom_sous_categorie FROM __temp__sous_categorie');
        $this->addSql('DROP TABLE __temp__sous_categorie');
        $this->addSql('CREATE INDEX IDX_52743D7BBCF5E72D ON sous_categorie (categorie_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, email, roles, password) SELECT id, email, roles, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }
}
