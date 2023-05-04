<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230504170646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_5FB6DEC7100D1FDF ON reponse');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5FB6DEC7100D1FDF ON reponse (id_reclamation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_5FB6DEC7100D1FDF ON reponse');
        $this->addSql('CREATE INDEX UNIQ_5FB6DEC7100D1FDF ON reponse (id_reclamation_id)');
    }
}
