<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250728031836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE alertes (id INT AUTO_INCREMENT NOT NULL, categorie VARCHAR(255) NOT NULL, prenoms VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) NOT NULL, telephone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, accord TINYINT(1) DEFAULT NULL, description LONGTEXT NOT NULL, salarie TINYINT(1) DEFAULT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE files ADD alertes_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE files ADD CONSTRAINT FK_6354059A5EFBEEF FOREIGN KEY (alertes_id) REFERENCES alertes (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6354059A5EFBEEF ON files (alertes_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE files DROP FOREIGN KEY FK_6354059A5EFBEEF
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE alertes
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_6354059A5EFBEEF ON files
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE files DROP alertes_id
        SQL);
    }
}
