<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230504144749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, catalogue_id INT DEFAULT NULL, rating INT NOT NULL, INDEX IDX_D88926227E3C61F9 (owner_id), INDEX IDX_D88926224A7843DC (catalogue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926227E3C61F9 FOREIGN KEY (owner_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926224A7843DC FOREIGN KEY (catalogue_id) REFERENCES catalogue (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE categorie DROP star_count, DROP rate');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_497DD6346C6E55B5 ON categorie (nom)');
        $this->addSql('ALTER TABLE produit ADD description VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926227E3C61F9');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926224A7843DC');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP INDEX UNIQ_497DD6346C6E55B5 ON categorie');
        $this->addSql('ALTER TABLE categorie ADD star_count INT DEFAULT NULL, ADD rate INT NOT NULL');
        $this->addSql('ALTER TABLE produit DROP description');
    }
}
