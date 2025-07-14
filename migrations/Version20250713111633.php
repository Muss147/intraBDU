<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250713111633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE offres_emploi (id INT AUTO_INCREMENT NOT NULL, direction_id INT NOT NULL, secteur_activite_id INT DEFAULT NULL, metier_id INT NOT NULL, titre VARCHAR(255) NOT NULL, lieu VARCHAR(255) DEFAULT NULL, experience VARCHAR(255) NOT NULL, niveau_poste VARCHAR(255) NOT NULL, niveau_formation VARCHAR(255) NOT NULL, INDEX IDX_8F915340AF73D997 (direction_id), INDEX IDX_8F9153405233A7FC (secteur_activite_id), INDEX IDX_8F915340ED16FA20 (metier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offres_emploi ADD CONSTRAINT FK_8F915340AF73D997 FOREIGN KEY (direction_id) REFERENCES parametres (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offres_emploi ADD CONSTRAINT FK_8F9153405233A7FC FOREIGN KEY (secteur_activite_id) REFERENCES parametres (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offres_emploi ADD CONSTRAINT FK_8F915340ED16FA20 FOREIGN KEY (metier_id) REFERENCES parametres (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE offres_emploi DROP FOREIGN KEY FK_8F915340AF73D997
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offres_emploi DROP FOREIGN KEY FK_8F9153405233A7FC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offres_emploi DROP FOREIGN KEY FK_8F915340ED16FA20
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE offres_emploi
        SQL);
    }
}
