<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230425143235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE album (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, nom VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, INDEX IDX_39986E4379F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalogue (id INT AUTO_INCREMENT NOT NULL, id_categorie_id INT NOT NULL, nom VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_59A699F59F34925F (id_categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, visiteur INT DEFAULT NULL, UNIQUE INDEX UNIQ_497DD6346C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, totale INT NOT NULL, date_commande DATE NOT NULL, INDEX IDX_6EEAA67D79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE detail_commande (id INT AUTO_INCREMENT NOT NULL, id_produit_id INT NOT NULL, id_commande_id INT NOT NULL, quantite INT NOT NULL, INDEX IDX_98344FA6AABEFE2C (id_produit_id), INDEX IDX_98344FA69AF8E3A3 (id_commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, date_evenement DATE NOT NULL, description LONGTEXT NOT NULL, photo VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, prix NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favori (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_musique_id INT NOT NULL, date_favori DATE NOT NULL, INDEX IDX_EF85A2CC79F37AE5 (id_user_id), INDEX IDX_EF85A2CC8068D476 (id_musique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE musique (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_categorie_id INT NOT NULL, id_album_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, chemin VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, longueur VARCHAR(255) NOT NULL, INDEX IDX_EE1D56BC79F37AE5 (id_user_id), INDEX IDX_EE1D56BC9F34925F (id_categorie_id), INDEX IDX_EE1D56BC41EC475A (id_album_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, id_evenement_id INT NOT NULL, id_utilisateur_id INT NOT NULL, INDEX IDX_AB55E24F2C115A61 (id_evenement_id), INDEX IDX_AB55E24FC6EE5C49 (id_utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, prix INT NOT NULL, image VARCHAR(255) NOT NULL, libelle VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, quantite INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, catalogue_id INT DEFAULT NULL, rating INT NOT NULL, INDEX IDX_D88926227E3C61F9 (owner_id), INDEX IDX_D88926224A7843DC (catalogue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, description LONGTEXT NOT NULL, etat VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, date_update DATE DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_CE60640479F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, id_reclamation_id INT NOT NULL, id_user_id INT NOT NULL, message LONGTEXT NOT NULL, date_creation DATE NOT NULL, date_update DATE DEFAULT NULL, UNIQUE INDEX UNIQ_5FB6DEC7100D1FDF (id_reclamation_id), INDEX IDX_5FB6DEC779F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, id_user_id INT NOT NULL, id_evenement_id INT NOT NULL, INDEX IDX_42C8495579F37AE5 (id_user_id), INDEX IDX_42C849552C115A61 (id_evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, login VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE album ADD CONSTRAINT FK_39986E4379F37AE5 FOREIGN KEY (id_user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE catalogue ADD CONSTRAINT FK_59A699F59F34925F FOREIGN KEY (id_categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D79F37AE5 FOREIGN KEY (id_user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE detail_commande ADD CONSTRAINT FK_98344FA6AABEFE2C FOREIGN KEY (id_produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE detail_commande ADD CONSTRAINT FK_98344FA69AF8E3A3 FOREIGN KEY (id_commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE favori ADD CONSTRAINT FK_EF85A2CC79F37AE5 FOREIGN KEY (id_user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE favori ADD CONSTRAINT FK_EF85A2CC8068D476 FOREIGN KEY (id_musique_id) REFERENCES musique (id)');
        $this->addSql('ALTER TABLE musique ADD CONSTRAINT FK_EE1D56BC79F37AE5 FOREIGN KEY (id_user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE musique ADD CONSTRAINT FK_EE1D56BC9F34925F FOREIGN KEY (id_categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE musique ADD CONSTRAINT FK_EE1D56BC41EC475A FOREIGN KEY (id_album_id) REFERENCES album (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F2C115A61 FOREIGN KEY (id_evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FC6EE5C49 FOREIGN KEY (id_utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926227E3C61F9 FOREIGN KEY (owner_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926224A7843DC FOREIGN KEY (catalogue_id) REFERENCES catalogue (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640479F37AE5 FOREIGN KEY (id_user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC7100D1FDF FOREIGN KEY (id_reclamation_id) REFERENCES reclamation (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC779F37AE5 FOREIGN KEY (id_user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495579F37AE5 FOREIGN KEY (id_user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849552C115A61 FOREIGN KEY (id_evenement_id) REFERENCES evenement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album DROP FOREIGN KEY FK_39986E4379F37AE5');
        $this->addSql('ALTER TABLE catalogue DROP FOREIGN KEY FK_59A699F59F34925F');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D79F37AE5');
        $this->addSql('ALTER TABLE detail_commande DROP FOREIGN KEY FK_98344FA6AABEFE2C');
        $this->addSql('ALTER TABLE detail_commande DROP FOREIGN KEY FK_98344FA69AF8E3A3');
        $this->addSql('ALTER TABLE favori DROP FOREIGN KEY FK_EF85A2CC79F37AE5');
        $this->addSql('ALTER TABLE favori DROP FOREIGN KEY FK_EF85A2CC8068D476');
        $this->addSql('ALTER TABLE musique DROP FOREIGN KEY FK_EE1D56BC79F37AE5');
        $this->addSql('ALTER TABLE musique DROP FOREIGN KEY FK_EE1D56BC9F34925F');
        $this->addSql('ALTER TABLE musique DROP FOREIGN KEY FK_EE1D56BC41EC475A');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F2C115A61');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FC6EE5C49');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926227E3C61F9');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926224A7843DC');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640479F37AE5');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC7100D1FDF');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC779F37AE5');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495579F37AE5');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849552C115A61');
        $this->addSql('DROP TABLE album');
        $this->addSql('DROP TABLE catalogue');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE detail_commande');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE favori');
        $this->addSql('DROP TABLE musique');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
