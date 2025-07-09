<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250702032317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE documents (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, source_id INT NOT NULL, created_user_id INT DEFAULT NULL, updated_user_id INT DEFAULT NULL, deleted_user_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_A2B07288727ACA70 (parent_id), UNIQUE INDEX UNIQ_A2B07288953C1C61 (source_id), INDEX IDX_A2B07288E104C1D3 (created_user_id), INDEX IDX_A2B07288BB649746 (updated_user_id), INDEX IDX_A2B07288FDE969F2 (deleted_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE documents ADD CONSTRAINT FK_A2B07288727ACA70 FOREIGN KEY (parent_id) REFERENCES documents (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE documents ADD CONSTRAINT FK_A2B07288953C1C61 FOREIGN KEY (source_id) REFERENCES files (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE documents ADD CONSTRAINT FK_A2B07288E104C1D3 FOREIGN KEY (created_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE documents ADD CONSTRAINT FK_A2B07288BB649746 FOREIGN KEY (updated_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE documents ADD CONSTRAINT FK_A2B07288FDE969F2 FOREIGN KEY (deleted_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE documents DROP FOREIGN KEY FK_A2B07288727ACA70
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE documents DROP FOREIGN KEY FK_A2B07288953C1C61
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE documents DROP FOREIGN KEY FK_A2B07288E104C1D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE documents DROP FOREIGN KEY FK_A2B07288BB649746
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE documents DROP FOREIGN KEY FK_A2B07288FDE969F2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE documents
        SQL);
    }
}
