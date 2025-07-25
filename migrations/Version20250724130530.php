<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250724130530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE postuler (id INT AUTO_INCREMENT NOT NULL, offre_id INT NOT NULL, cv_id INT DEFAULT NULL, nom_agent VARCHAR(255) NOT NULL, poste_occupe VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) DEFAULT NULL, matricule VARCHAR(255) DEFAULT NULL, INDEX IDX_8EC5A68D4CC8505A (offre_id), UNIQUE INDEX UNIQ_8EC5A68DCFE419E2 (cv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE postuler ADD CONSTRAINT FK_8EC5A68D4CC8505A FOREIGN KEY (offre_id) REFERENCES offres_emploi (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE postuler ADD CONSTRAINT FK_8EC5A68DCFE419E2 FOREIGN KEY (cv_id) REFERENCES files (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE postuler DROP FOREIGN KEY FK_8EC5A68D4CC8505A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE postuler DROP FOREIGN KEY FK_8EC5A68DCFE419E2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE postuler
        SQL);
    }
}
