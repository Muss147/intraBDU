<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250713010858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE classement_mensuel (id INT AUTO_INCREMENT NOT NULL, agent_id INT DEFAULT NULL, mois DATE NOT NULL, moyenne DOUBLE PRECISION NOT NULL, nombre_votes INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_130CCB643414710B (agent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE classement_mensuel ADD CONSTRAINT FK_130CCB643414710B FOREIGN KEY (agent_id) REFERENCES agents (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE classement_mensuel DROP FOREIGN KEY FK_130CCB643414710B
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE classement_mensuel
        SQL);
    }
}
