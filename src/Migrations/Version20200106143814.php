<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200106143814 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE t_entite_administrative ADD fkEntitemere VARCHAR(50) DEFAULT NULL, DROP fk_entitemere, CHANGE IdEntite IdEntite VARCHAR(50) NOT NULL, CHANGE fkTypeentite fkTypeentite VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE t_entite_administrative ADD CONSTRAINT FK_511BD52773A90129 FOREIGN KEY (fkEntitemere) REFERENCES t_entite_administrative (IdEntite)');
        $this->addSql('CREATE INDEX IDX_511BD52773A90129 ON t_entite_administrative (fkEntitemere)');
        $this->addSql('ALTER TABLE t_type_entite_administrative CHANGE IdTypeEntite IdTypeEntite VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE t_typeterritorial CHANGE IdTypeTerritorial IdTypeTerritorial VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE t_entite_administrative DROP FOREIGN KEY FK_511BD52773A90129');
        $this->addSql('DROP INDEX IDX_511BD52773A90129 ON t_entite_administrative');
        $this->addSql('ALTER TABLE t_entite_administrative ADD fk_entitemere VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP fkEntitemere, CHANGE IdEntite IdEntite VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE fkTypeentite fkTypeentite VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE t_type_entite_administrative CHANGE IdTypeEntite IdTypeEntite VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE t_typeterritorial CHANGE IdTypeTerritorial IdTypeTerritorial VARCHAR(50) CHARACTER SET utf8 NOT NULL COLLATE `utf8_bin`');
    }
}
