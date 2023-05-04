<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230424013624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande CHANGE dateCommande date_commande DATE NOT NULL');
        $this->addSql('ALTER TABLE evenement ADD likes INT NOT NULL, CHANGE date_evenement date_evenement DATE NOT NULL, CHANGE titre titre VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit DROP description, DROP taille, CHANGE qte quantite INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande CHANGE date_commande dateCommande DATE NOT NULL');
        $this->addSql('ALTER TABLE evenement DROP likes, CHANGE date_evenement date_evenement DATE DEFAULT NULL, CHANGE titre titre VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD description VARCHAR(255) NOT NULL, ADD taille VARCHAR(255) NOT NULL, CHANGE quantite qte INT NOT NULL');
    }
}
