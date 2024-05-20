<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240520013330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__achat AS SELECT id, qte, livre_id, commande_id FROM achat');
        $this->addSql('DROP TABLE achat');
        $this->addSql('CREATE TABLE achat (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, qte INTEGER NOT NULL, livre_id INTEGER NOT NULL, commande_id INTEGER NOT NULL, CONSTRAINT FK_26A9845637D925CB FOREIGN KEY (livre_id) REFERENCES livres (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_26A9845682EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO achat (id, qte, livre_id, commande_id) SELECT id, qte, livre_id, commande_id FROM __temp__achat');
        $this->addSql('DROP TABLE __temp__achat');
        $this->addSql('CREATE INDEX IDX_26A9845682EA2E54 ON achat (commande_id)');
        $this->addSql('CREATE INDEX IDX_26A9845637D925CB ON achat (livre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__achat AS SELECT id, qte, livre_id, commande_id FROM achat');
        $this->addSql('DROP TABLE achat');
        $this->addSql('CREATE TABLE achat (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, qte INTEGER NOT NULL, livre_id INTEGER NOT NULL, commande_id INTEGER NOT NULL, CONSTRAINT FK_26A9845637D925CB FOREIGN KEY (livre_id) REFERENCES livres (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_26A9845682EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO achat (id, qte, livre_id, commande_id) SELECT id, qte, livre_id, commande_id FROM __temp__achat');
        $this->addSql('DROP TABLE __temp__achat');
        $this->addSql('CREATE INDEX IDX_26A9845682EA2E54 ON achat (commande_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_26A9845637D925CB ON achat (livre_id)');
    }
}
