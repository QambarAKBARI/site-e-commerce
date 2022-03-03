<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220302123300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_8F91ABF0A76ED395');
        $this->addSql('DROP INDEX IDX_8F91ABF0F347EFB');
        $this->addSql('CREATE TEMPORARY TABLE __temp__avis AS SELECT id, user_id, produit_id, texte, note, date_depot FROM avis');
        $this->addSql('DROP TABLE avis');
        $this->addSql('CREATE TABLE avis (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, produit_id INTEGER NOT NULL, texte CLOB NOT NULL, note INTEGER NOT NULL, date_depot DATETIME NOT NULL, CONSTRAINT FK_8F91ABF0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_8F91ABF0F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO avis (id, user_id, produit_id, texte, note, date_depot) SELECT id, user_id, produit_id, texte, note, date_depot FROM __temp__avis');
        $this->addSql('DROP TABLE __temp__avis');
        $this->addSql('CREATE INDEX IDX_8F91ABF0A76ED395 ON avis (user_id)');
        $this->addSql('CREATE INDEX IDX_8F91ABF0F347EFB ON avis (produit_id)');
        $this->addSql('DROP INDEX IDX_6EEAA67DA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__commande AS SELECT id, user_id, date_commande, adresse_livraison, cp_livraison, ville_livraison, status, total FROM commande');
        $this->addSql('DROP TABLE commande');
        $this->addSql('CREATE TABLE commande (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, date_commande DATETIME NOT NULL, adresse_livraison VARCHAR(255) NOT NULL, cp_livraison VARCHAR(50) NOT NULL, ville_livraison VARCHAR(50) NOT NULL, status VARCHAR(100) NOT NULL, total INTEGER DEFAULT NULL, CONSTRAINT FK_6EEAA67DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO commande (id, user_id, date_commande, adresse_livraison, cp_livraison, ville_livraison, status, total) SELECT id, user_id, date_commande, adresse_livraison, cp_livraison, ville_livraison, status, total FROM __temp__commande');
        $this->addSql('DROP TABLE __temp__commande');
        $this->addSql('CREATE INDEX IDX_6EEAA67DA76ED395 ON commande (user_id)');
        $this->addSql('DROP INDEX IDX_29A5EC274827B9B2');
        $this->addSql('DROP INDEX IDX_29A5EC27365BF48');
        $this->addSql('CREATE TEMPORARY TABLE __temp__produit AS SELECT id, marque_id, sous_categorie_id, prix, image, nom_produit, quantite, description, status FROM produit');
        $this->addSql('DROP TABLE produit');
        $this->addSql('CREATE TABLE produit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, marque_id INTEGER DEFAULT NULL, sous_categorie_id INTEGER DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, image VARCHAR(255) DEFAULT NULL, nom_produit VARCHAR(255) NOT NULL, quantite INTEGER NOT NULL, description CLOB DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_29A5EC274827B9B2 FOREIGN KEY (marque_id) REFERENCES marque (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_29A5EC27365BF48 FOREIGN KEY (sous_categorie_id) REFERENCES sous_categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO produit (id, marque_id, sous_categorie_id, prix, image, nom_produit, quantite, description, status) SELECT id, marque_id, sous_categorie_id, prix, image, nom_produit, quantite, description, status FROM __temp__produit');
        $this->addSql('DROP TABLE __temp__produit');
        $this->addSql('CREATE INDEX IDX_29A5EC274827B9B2 ON produit (marque_id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27365BF48 ON produit (sous_categorie_id)');
        $this->addSql('DROP INDEX IDX_47F5946E82EA2E54');
        $this->addSql('DROP INDEX IDX_47F5946EF347EFB');
        $this->addSql('CREATE TEMPORARY TABLE __temp__produit_commande AS SELECT id, commande_id, produit_id, quantite FROM produit_commande');
        $this->addSql('DROP TABLE produit_commande');
        $this->addSql('CREATE TABLE produit_commande (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, commande_id INTEGER NOT NULL, produit_id INTEGER NOT NULL, quantite INTEGER NOT NULL, total INTEGER DEFAULT NULL, CONSTRAINT FK_47F5946E82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_47F5946EF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO produit_commande (id, commande_id, produit_id, quantite) SELECT id, commande_id, produit_id, quantite FROM __temp__produit_commande');
        $this->addSql('DROP TABLE __temp__produit_commande');
        $this->addSql('CREATE INDEX IDX_47F5946E82EA2E54 ON produit_commande (commande_id)');
        $this->addSql('CREATE INDEX IDX_47F5946EF347EFB ON produit_commande (produit_id)');
        $this->addSql('DROP INDEX IDX_52743D7BBCF5E72D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sous_categorie AS SELECT id, categorie_id, nom_sous_categorie FROM sous_categorie');
        $this->addSql('DROP TABLE sous_categorie');
        $this->addSql('CREATE TABLE sous_categorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categorie_id INTEGER DEFAULT NULL, nom_sous_categorie VARCHAR(100) DEFAULT NULL, CONSTRAINT FK_52743D7BBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sous_categorie (id, categorie_id, nom_sous_categorie) SELECT id, categorie_id, nom_sous_categorie FROM __temp__sous_categorie');
        $this->addSql('DROP TABLE __temp__sous_categorie');
        $this->addSql('CREATE INDEX IDX_52743D7BBCF5E72D ON sous_categorie (categorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_8F91ABF0A76ED395');
        $this->addSql('DROP INDEX IDX_8F91ABF0F347EFB');
        $this->addSql('CREATE TEMPORARY TABLE __temp__avis AS SELECT id, user_id, produit_id, texte, note, date_depot FROM avis');
        $this->addSql('DROP TABLE avis');
        $this->addSql('CREATE TABLE avis (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, produit_id INTEGER NOT NULL, texte CLOB NOT NULL, note INTEGER NOT NULL, date_depot DATETIME NOT NULL)');
        $this->addSql('INSERT INTO avis (id, user_id, produit_id, texte, note, date_depot) SELECT id, user_id, produit_id, texte, note, date_depot FROM __temp__avis');
        $this->addSql('DROP TABLE __temp__avis');
        $this->addSql('CREATE INDEX IDX_8F91ABF0A76ED395 ON avis (user_id)');
        $this->addSql('CREATE INDEX IDX_8F91ABF0F347EFB ON avis (produit_id)');
        $this->addSql('DROP INDEX IDX_6EEAA67DA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__commande AS SELECT id, user_id, date_commande, adresse_livraison, cp_livraison, ville_livraison, status, total FROM commande');
        $this->addSql('DROP TABLE commande');
        $this->addSql('CREATE TABLE commande (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, date_commande DATETIME NOT NULL, adresse_livraison VARCHAR(255) NOT NULL, cp_livraison VARCHAR(50) NOT NULL, ville_livraison VARCHAR(50) NOT NULL, status VARCHAR(100) NOT NULL, total INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO commande (id, user_id, date_commande, adresse_livraison, cp_livraison, ville_livraison, status, total) SELECT id, user_id, date_commande, adresse_livraison, cp_livraison, ville_livraison, status, total FROM __temp__commande');
        $this->addSql('DROP TABLE __temp__commande');
        $this->addSql('CREATE INDEX IDX_6EEAA67DA76ED395 ON commande (user_id)');
        $this->addSql('DROP INDEX IDX_29A5EC274827B9B2');
        $this->addSql('DROP INDEX IDX_29A5EC27365BF48');
        $this->addSql('CREATE TEMPORARY TABLE __temp__produit AS SELECT id, marque_id, sous_categorie_id, prix, image, nom_produit, quantite, description, status FROM produit');
        $this->addSql('DROP TABLE produit');
        $this->addSql('CREATE TABLE produit (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, marque_id INTEGER DEFAULT NULL, sous_categorie_id INTEGER DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, image VARCHAR(255) DEFAULT NULL, nom_produit VARCHAR(255) NOT NULL, quantite INTEGER NOT NULL, description CLOB DEFAULT NULL, status VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO produit (id, marque_id, sous_categorie_id, prix, image, nom_produit, quantite, description, status) SELECT id, marque_id, sous_categorie_id, prix, image, nom_produit, quantite, description, status FROM __temp__produit');
        $this->addSql('DROP TABLE __temp__produit');
        $this->addSql('CREATE INDEX IDX_29A5EC274827B9B2 ON produit (marque_id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27365BF48 ON produit (sous_categorie_id)');
        $this->addSql('DROP INDEX IDX_47F5946E82EA2E54');
        $this->addSql('DROP INDEX IDX_47F5946EF347EFB');
        $this->addSql('CREATE TEMPORARY TABLE __temp__produit_commande AS SELECT id, commande_id, produit_id, quantite FROM produit_commande');
        $this->addSql('DROP TABLE produit_commande');
        $this->addSql('CREATE TABLE produit_commande (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, commande_id INTEGER NOT NULL, produit_id INTEGER NOT NULL, quantite INTEGER NOT NULL)');
        $this->addSql('INSERT INTO produit_commande (id, commande_id, produit_id, quantite) SELECT id, commande_id, produit_id, quantite FROM __temp__produit_commande');
        $this->addSql('DROP TABLE __temp__produit_commande');
        $this->addSql('CREATE INDEX IDX_47F5946E82EA2E54 ON produit_commande (commande_id)');
        $this->addSql('CREATE INDEX IDX_47F5946EF347EFB ON produit_commande (produit_id)');
        $this->addSql('DROP INDEX IDX_52743D7BBCF5E72D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sous_categorie AS SELECT id, categorie_id, nom_sous_categorie FROM sous_categorie');
        $this->addSql('DROP TABLE sous_categorie');
        $this->addSql('CREATE TABLE sous_categorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categorie_id INTEGER DEFAULT NULL, nom_sous_categorie VARCHAR(100) DEFAULT NULL)');
        $this->addSql('INSERT INTO sous_categorie (id, categorie_id, nom_sous_categorie) SELECT id, categorie_id, nom_sous_categorie FROM __temp__sous_categorie');
        $this->addSql('DROP TABLE __temp__sous_categorie');
        $this->addSql('CREATE INDEX IDX_52743D7BBCF5E72D ON sous_categorie (categorie_id)');
    }
}
