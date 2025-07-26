<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250726021251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents_params ADD parent_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents_params ADD CONSTRAINT FK_7FEE84B4727ACA70 FOREIGN KEY (parent_id) REFERENCES incidents_params (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_7FEE84B4727ACA70 ON incidents_params (parent_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents_params DROP FOREIGN KEY FK_7FEE84B4727ACA70
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_7FEE84B4727ACA70 ON incidents_params
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents_params DROP parent_id
        SQL);
    }
}
