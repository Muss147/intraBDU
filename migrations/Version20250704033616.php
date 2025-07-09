<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250704033616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE agents ADD photo_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agents ADD CONSTRAINT FK_9596AB6E7E9E4C8C FOREIGN KEY (photo_id) REFERENCES files (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_9596AB6E7E9E4C8C ON agents (photo_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE agents DROP FOREIGN KEY FK_9596AB6E7E9E4C8C
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_9596AB6E7E9E4C8C ON agents
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agents DROP photo_id
        SQL);
    }
}
