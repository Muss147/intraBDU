<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250724014726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE offres_emploi DROP FOREIGN KEY FK_8F9153405233A7FC
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8F9153405233A7FC ON offres_emploi
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offres_emploi DROP secteur_activite_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE offres_emploi ADD secteur_activite_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offres_emploi ADD CONSTRAINT FK_8F9153405233A7FC FOREIGN KEY (secteur_activite_id) REFERENCES parametres (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8F9153405233A7FC ON offres_emploi (secteur_activite_id)
        SQL);
    }
}
