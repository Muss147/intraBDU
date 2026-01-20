<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260116021921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_impact (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, month VARCHAR(20) NOT NULL, is_published TINYINT(1) NOT NULL, is_archived TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D3AD2B4D7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_result (id INT AUTO_INCREMENT NOT NULL, project_title VARCHAR(255) NOT NULL, owner_name VARCHAR(255) NOT NULL, votes_count INT NOT NULL, month VARCHAR(20) NOT NULL, archived_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_vote (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, voter_hash VARCHAR(64) NOT NULL, voted_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_63814D7C166D1F9C (project_id), UNIQUE INDEX UNIQ_63814D7C166D1F9C66C66680 (project_id, voter_hash), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_impact ADD CONSTRAINT FK_D3AD2B4D7E3C61F9 FOREIGN KEY (owner_id) REFERENCES agents (id)');
        $this->addSql('ALTER TABLE project_vote ADD CONSTRAINT FK_63814D7C166D1F9C FOREIGN KEY (project_id) REFERENCES project_impact (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_impact DROP FOREIGN KEY FK_D3AD2B4D7E3C61F9');
        $this->addSql('ALTER TABLE project_vote DROP FOREIGN KEY FK_63814D7C166D1F9C');
        $this->addSql('DROP TABLE project_impact');
        $this->addSql('DROP TABLE project_result');
        $this->addSql('DROP TABLE project_vote');
    }
}
