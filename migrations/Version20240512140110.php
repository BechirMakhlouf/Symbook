<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240512140110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__panier AS SELECT id FROM panier');
        $this->addSql('DROP TABLE panier');
        $this->addSql('CREATE TABLE panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, CONSTRAINT FK_24CC0DF2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO panier (id) SELECT id FROM __temp__panier');
        $this->addSql('DROP TABLE __temp__panier');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_24CC0DF2A76ED395 ON panier (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__panier_item AS SELECT id, quantite, livre_id FROM panier_item');
        $this->addSql('DROP TABLE panier_item');
        $this->addSql('CREATE TABLE panier_item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, quantite INTEGER NOT NULL, livre_id INTEGER NOT NULL, panier_id INTEGER NOT NULL, CONSTRAINT FK_EBFD006737D925CB FOREIGN KEY (livre_id) REFERENCES livres (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_EBFD0067F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO panier_item (id, quantite, livre_id) SELECT id, quantite, livre_id FROM __temp__panier_item');
        $this->addSql('DROP TABLE __temp__panier_item');
        $this->addSql('CREATE INDEX IDX_EBFD006737D925CB ON panier_item (livre_id)');
        $this->addSql('CREATE INDEX IDX_EBFD0067F77D927C ON panier_item (panier_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__panier AS SELECT id FROM panier');
        $this->addSql('DROP TABLE panier');
        $this->addSql('CREATE TABLE panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('INSERT INTO panier (id) SELECT id FROM __temp__panier');
        $this->addSql('DROP TABLE __temp__panier');
        $this->addSql('CREATE TEMPORARY TABLE __temp__panier_item AS SELECT id, quantite, livre_id FROM panier_item');
        $this->addSql('DROP TABLE panier_item');
        $this->addSql('CREATE TABLE panier_item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, quantite INTEGER NOT NULL, livre_id INTEGER NOT NULL, CONSTRAINT FK_EBFD006737D925CB FOREIGN KEY (livre_id) REFERENCES livres (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO panier_item (id, quantite, livre_id) SELECT id, quantite, livre_id FROM __temp__panier_item');
        $this->addSql('DROP TABLE __temp__panier_item');
        $this->addSql('CREATE INDEX IDX_EBFD006737D925CB ON panier_item (livre_id)');
    }
}
