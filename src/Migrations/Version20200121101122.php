<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200121101122 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE adresse_personne (adresse_id INT NOT NULL, personne_id INT NOT NULL, INDEX IDX_6FD059854DE7DC5C (adresse_id), INDEX IDX_6FD05985A21BD112 (personne_id), PRIMARY KEY(adresse_id, personne_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adresse_personne ADD CONSTRAINT FK_6FD059854DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adresse_personne ADD CONSTRAINT FK_6FD05985A21BD112 FOREIGN KEY (personne_id) REFERENCES personne (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adresse DROP FOREIGN KEY FK_C35F0816A21BD112');
        $this->addSql('DROP INDEX IDX_C35F0816A21BD112 ON adresse');
        $this->addSql('ALTER TABLE adresse ADD fk_entite VARCHAR(50) DEFAULT NULL, ADD adresse_complete LONGTEXT DEFAULT NULL, ADD etat INT DEFAULT NULL, DROP province, DROP district, DROP commune, DROP quartier, DROP avenue, CHANGE numero numero VARCHAR(255) DEFAULT NULL, CHANGE personne_id par_defaut INT DEFAULT NULL');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F08163A8770B7 FOREIGN KEY (fk_entite) REFERENCES T_Entite_Administrative (IdEntite)');
        $this->addSql('CREATE INDEX IDX_C35F08163A8770B7 ON adresse (fk_entite)');
        $this->addSql('ALTER TABLE t_entite_administrative CHANGE IdEntite IdEntite VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE t_type_entite_administrative CHANGE IdTypeEntite IdTypeEntite VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE t_typeterritorial CHANGE IdTypeTerritorial IdTypeTerritorial VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE adresse_personne');
        $this->addSql('ALTER TABLE adresse DROP FOREIGN KEY FK_C35F08163A8770B7');
        $this->addSql('DROP INDEX IDX_C35F08163A8770B7 ON adresse');
        $this->addSql('ALTER TABLE adresse ADD personne_id INT DEFAULT NULL, ADD province VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD district VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD commune VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD quartier VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD avenue VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP fk_entite, DROP adresse_complete, DROP par_defaut, DROP etat, CHANGE numero numero VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F0816A21BD112 FOREIGN KEY (personne_id) REFERENCES personne (id)');
        $this->addSql('CREATE INDEX IDX_C35F0816A21BD112 ON adresse (personne_id)');
        $this->addSql('ALTER TABLE T_Entite_Administrative CHANGE IdEntite IdEntite VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE T_Type_Entite_Administrative CHANGE IdTypeEntite IdTypeEntite VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE T_TypeTerritorial CHANGE IdTypeTerritorial IdTypeTerritorial VARCHAR(50) CHARACTER SET utf8 NOT NULL COLLATE `utf8_bin`');
    }
}
