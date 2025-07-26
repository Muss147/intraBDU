<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250726020821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE incidents (id INT AUTO_INCREMENT NOT NULL, direction_id INT DEFAULT NULL, direction_impacte_id INT NOT NULL, categorie_id INT NOT NULL, sous_categorie_id INT NOT NULL, processus_id INT NOT NULL, sous_processus_id INT NOT NULL, etape VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, date_debut DATETIME DEFAULT NULL, date_remonte DATETIME NOT NULL, agence VARCHAR(255) DEFAULT NULL, region VARCHAR(255) DEFAULT NULL, rapporteur VARCHAR(255) NOT NULL, fonction VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, description LONGTEXT NOT NULL, montant_estime INT DEFAULT NULL, date_perte DATETIME DEFAULT NULL, montant_obtenu INT DEFAULT NULL, date_recup DATETIME DEFAULT NULL, date_compta DATETIME DEFAULT NULL, nature_recup VARCHAR(255) DEFAULT NULL, montant_net INT DEFAULT NULL, consequences LONGTEXT DEFAULT NULL, solutions JSON DEFAULT NULL, INDEX IDX_E65135D0AF73D997 (direction_id), INDEX IDX_E65135D03B05134D (direction_impacte_id), INDEX IDX_E65135D0BCF5E72D (categorie_id), INDEX IDX_E65135D0365BF48 (sous_categorie_id), INDEX IDX_E65135D0A55629DC (processus_id), INDEX IDX_E65135D01AC671B9 (sous_processus_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE incidents_params (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents ADD CONSTRAINT FK_E65135D0AF73D997 FOREIGN KEY (direction_id) REFERENCES parametres (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents ADD CONSTRAINT FK_E65135D03B05134D FOREIGN KEY (direction_impacte_id) REFERENCES parametres (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents ADD CONSTRAINT FK_E65135D0BCF5E72D FOREIGN KEY (categorie_id) REFERENCES incidents_params (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents ADD CONSTRAINT FK_E65135D0365BF48 FOREIGN KEY (sous_categorie_id) REFERENCES incidents_params (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents ADD CONSTRAINT FK_E65135D0A55629DC FOREIGN KEY (processus_id) REFERENCES incidents_params (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents ADD CONSTRAINT FK_E65135D01AC671B9 FOREIGN KEY (sous_processus_id) REFERENCES incidents_params (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents DROP FOREIGN KEY FK_E65135D0AF73D997
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents DROP FOREIGN KEY FK_E65135D03B05134D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents DROP FOREIGN KEY FK_E65135D0BCF5E72D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents DROP FOREIGN KEY FK_E65135D0365BF48
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents DROP FOREIGN KEY FK_E65135D0A55629DC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents DROP FOREIGN KEY FK_E65135D01AC671B9
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE incidents
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE incidents_params
        SQL);
    }
}
