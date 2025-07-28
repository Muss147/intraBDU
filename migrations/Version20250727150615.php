<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250727150615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents ADD created_user_id INT DEFAULT NULL, ADD updated_user_id INT DEFAULT NULL, ADD deleted_user_id INT DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL, ADD deleted_at DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents ADD CONSTRAINT FK_E65135D0E104C1D3 FOREIGN KEY (created_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents ADD CONSTRAINT FK_E65135D0BB649746 FOREIGN KEY (updated_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents ADD CONSTRAINT FK_E65135D0FDE969F2 FOREIGN KEY (deleted_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E65135D0E104C1D3 ON incidents (created_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E65135D0BB649746 ON incidents (updated_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E65135D0FDE969F2 ON incidents (deleted_user_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents DROP FOREIGN KEY FK_E65135D0E104C1D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents DROP FOREIGN KEY FK_E65135D0BB649746
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents DROP FOREIGN KEY FK_E65135D0FDE969F2
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E65135D0E104C1D3 ON incidents
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E65135D0BB649746 ON incidents
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E65135D0FDE969F2 ON incidents
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents DROP created_user_id, DROP updated_user_id, DROP deleted_user_id, DROP created_at, DROP updated_at, DROP deleted_at
        SQL);
    }
}
