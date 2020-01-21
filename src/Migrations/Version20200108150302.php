<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200108150302 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE t_entite_administrative DROP FOREIGN KEY FK_511BD527FD850611');
        $this->addSql('DROP INDEX IDX_511BD527FD850611 ON t_entite_administrative');
        $this->addSql('ALTER TABLE t_entite_administrative CHANGE IdEntite IdEntite VARCHAR(50) NOT NULL, CHANGE fktypeentite Fk_TypeEntite VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE t_entite_administrative ADD CONSTRAINT FK_95284BF95FCF4CC FOREIGN KEY (Fk_TypeEntite) REFERENCES T_Type_Entite_Administrative (IdTypeEntite)');
        $this->addSql('CREATE INDEX IDX_95284BF95FCF4CC ON t_entite_administrative (Fk_TypeEntite)');
        $this->addSql('ALTER TABLE t_type_entite_administrative CHANGE IdTypeEntite IdTypeEntite VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE t_typeterritorial CHANGE IdTypeTerritorial IdTypeTerritorial VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE T_Entite_Administrative DROP FOREIGN KEY FK_95284BF95FCF4CC');
        $this->addSql('DROP INDEX IDX_95284BF95FCF4CC ON T_Entite_Administrative');
        $this->addSql('ALTER TABLE T_Entite_Administrative CHANGE IdEntite IdEntite VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE fk_typeentite fkTypeentite VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE T_Entite_Administrative ADD CONSTRAINT FK_511BD527FD850611 FOREIGN KEY (fkTypeentite) REFERENCES t_type_entite_administrative (IdTypeEntite)');
        $this->addSql('CREATE INDEX IDX_511BD527FD850611 ON T_Entite_Administrative (fkTypeentite)');
        $this->addSql('ALTER TABLE T_Type_Entite_Administrative CHANGE IdTypeEntite IdTypeEntite VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE T_Typeterritorial CHANGE IdTypeTerritorial IdTypeTerritorial VARCHAR(50) CHARACTER SET utf8 NOT NULL COLLATE `utf8_bin`');
    }
}
