<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250704031403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE agents (id INT AUTO_INCREMENT NOT NULL, agence_id INT NOT NULL, direction_id INT NOT NULL, service_id INT DEFAULT NULL, created_user_id INT DEFAULT NULL, updated_user_id INT DEFAULT NULL, deleted_user_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenoms VARCHAR(255) NOT NULL, civilite VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, fixe VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, commune VARCHAR(255) DEFAULT NULL, poste VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_9596AB6ED725330D (agence_id), INDEX IDX_9596AB6EAF73D997 (direction_id), INDEX IDX_9596AB6EED5CA9E6 (service_id), INDEX IDX_9596AB6EE104C1D3 (created_user_id), INDEX IDX_9596AB6EBB649746 (updated_user_id), INDEX IDX_9596AB6EFDE969F2 (deleted_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agents ADD CONSTRAINT FK_9596AB6ED725330D FOREIGN KEY (agence_id) REFERENCES parametres (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agents ADD CONSTRAINT FK_9596AB6EAF73D997 FOREIGN KEY (direction_id) REFERENCES parametres (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agents ADD CONSTRAINT FK_9596AB6EED5CA9E6 FOREIGN KEY (service_id) REFERENCES parametres (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agents ADD CONSTRAINT FK_9596AB6EE104C1D3 FOREIGN KEY (created_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agents ADD CONSTRAINT FK_9596AB6EBB649746 FOREIGN KEY (updated_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agents ADD CONSTRAINT FK_9596AB6EFDE969F2 FOREIGN KEY (deleted_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE agents DROP FOREIGN KEY FK_9596AB6ED725330D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agents DROP FOREIGN KEY FK_9596AB6EAF73D997
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agents DROP FOREIGN KEY FK_9596AB6EED5CA9E6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agents DROP FOREIGN KEY FK_9596AB6EE104C1D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agents DROP FOREIGN KEY FK_9596AB6EBB649746
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agents DROP FOREIGN KEY FK_9596AB6EFDE969F2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE agents
        SQL);
    }
}
