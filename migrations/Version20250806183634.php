<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250806183634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE files ADD incidents_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE files ADD CONSTRAINT FK_635405955955332 FOREIGN KEY (incidents_id) REFERENCES incidents (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_635405955955332 ON files (incidents_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE files DROP FOREIGN KEY FK_635405955955332
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_635405955955332 ON files
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE files DROP incidents_id
        SQL);
    }
}
