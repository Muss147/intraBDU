<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260116214448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_impact ADD created_user_id INT DEFAULT NULL, ADD updated_user_id INT DEFAULT NULL, ADD deleted_user_id INT DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL, ADD deleted_at DATETIME DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE project_impact ADD CONSTRAINT FK_D3AD2B4DE104C1D3 FOREIGN KEY (created_user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_impact ADD CONSTRAINT FK_D3AD2B4DBB649746 FOREIGN KEY (updated_user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_impact ADD CONSTRAINT FK_D3AD2B4DFDE969F2 FOREIGN KEY (deleted_user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_D3AD2B4DE104C1D3 ON project_impact (created_user_id)');
        $this->addSql('CREATE INDEX IDX_D3AD2B4DBB649746 ON project_impact (updated_user_id)');
        $this->addSql('CREATE INDEX IDX_D3AD2B4DFDE969F2 ON project_impact (deleted_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_impact DROP FOREIGN KEY FK_D3AD2B4DE104C1D3');
        $this->addSql('ALTER TABLE project_impact DROP FOREIGN KEY FK_D3AD2B4DBB649746');
        $this->addSql('ALTER TABLE project_impact DROP FOREIGN KEY FK_D3AD2B4DFDE969F2');
        $this->addSql('DROP INDEX IDX_D3AD2B4DE104C1D3 ON project_impact');
        $this->addSql('DROP INDEX IDX_D3AD2B4DBB649746 ON project_impact');
        $this->addSql('DROP INDEX IDX_D3AD2B4DFDE969F2 ON project_impact');
        $this->addSql('ALTER TABLE project_impact DROP created_user_id, DROP updated_user_id, DROP deleted_user_id, DROP updated_at, DROP deleted_at, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
