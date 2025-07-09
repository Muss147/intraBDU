<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250707021635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE agents DROP FOREIGN KEY FK_9596AB6EAF73D997
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agents DROP FOREIGN KEY FK_9596AB6ED725330D
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9596AB6EAF73D997 ON agents
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9596AB6ED725330D ON agents
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agents DROP agence_id, DROP direction_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE agents ADD agence_id INT NOT NULL, ADD direction_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agents ADD CONSTRAINT FK_9596AB6EAF73D997 FOREIGN KEY (direction_id) REFERENCES parametres (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agents ADD CONSTRAINT FK_9596AB6ED725330D FOREIGN KEY (agence_id) REFERENCES parametres (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9596AB6EAF73D997 ON agents (direction_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9596AB6ED725330D ON agents (agence_id)
        SQL);
    }
}
