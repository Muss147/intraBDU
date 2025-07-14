<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250713143941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE offres_emploi ADD created_user_id INT DEFAULT NULL, ADD updated_user_id INT DEFAULT NULL, ADD deleted_user_id INT DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL, ADD deleted_at DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offres_emploi ADD CONSTRAINT FK_8F915340E104C1D3 FOREIGN KEY (created_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offres_emploi ADD CONSTRAINT FK_8F915340BB649746 FOREIGN KEY (updated_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offres_emploi ADD CONSTRAINT FK_8F915340FDE969F2 FOREIGN KEY (deleted_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8F915340E104C1D3 ON offres_emploi (created_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8F915340BB649746 ON offres_emploi (updated_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8F915340FDE969F2 ON offres_emploi (deleted_user_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE offres_emploi DROP FOREIGN KEY FK_8F915340E104C1D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offres_emploi DROP FOREIGN KEY FK_8F915340BB649746
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offres_emploi DROP FOREIGN KEY FK_8F915340FDE969F2
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8F915340E104C1D3 ON offres_emploi
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8F915340BB649746 ON offres_emploi
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8F915340FDE969F2 ON offres_emploi
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offres_emploi DROP created_user_id, DROP updated_user_id, DROP deleted_user_id, DROP created_at, DROP updated_at, DROP deleted_at
        SQL);
    }
}
