<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250731142231 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE agents ADD bureau_id INT DEFAULT NULL, DROP bureau
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agents ADD CONSTRAINT FK_9596AB6E32516FE2 FOREIGN KEY (bureau_id) REFERENCES parametres (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9596AB6E32516FE2 ON agents (bureau_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE agents DROP FOREIGN KEY FK_9596AB6E32516FE2
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9596AB6E32516FE2 ON agents
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agents ADD bureau VARCHAR(255) NOT NULL, DROP bureau_id
        SQL);
    }
}
