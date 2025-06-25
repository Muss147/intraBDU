<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250625111117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE actualites ADD created_user_id INT DEFAULT NULL, ADD updated_user_id INT DEFAULT NULL, ADD deleted_user_id INT DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL, ADD deleted_at DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actualites ADD CONSTRAINT FK_75315B6DE104C1D3 FOREIGN KEY (created_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actualites ADD CONSTRAINT FK_75315B6DBB649746 FOREIGN KEY (updated_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actualites ADD CONSTRAINT FK_75315B6DFDE969F2 FOREIGN KEY (deleted_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75315B6DE104C1D3 ON actualites (created_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75315B6DBB649746 ON actualites (updated_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75315B6DFDE969F2 ON actualites (deleted_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE banners ADD created_user_id INT DEFAULT NULL, ADD updated_user_id INT DEFAULT NULL, ADD deleted_user_id INT DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL, ADD deleted_at DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE banners ADD CONSTRAINT FK_250F2568E104C1D3 FOREIGN KEY (created_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE banners ADD CONSTRAINT FK_250F2568BB649746 FOREIGN KEY (updated_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE banners ADD CONSTRAINT FK_250F2568FDE969F2 FOREIGN KEY (deleted_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_250F2568E104C1D3 ON banners (created_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_250F2568BB649746 ON banners (updated_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_250F2568FDE969F2 ON banners (deleted_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notes_publications ADD created_user_id INT DEFAULT NULL, ADD updated_user_id INT DEFAULT NULL, ADD deleted_user_id INT DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL, ADD deleted_at DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notes_publications ADD CONSTRAINT FK_A72DFDF8E104C1D3 FOREIGN KEY (created_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notes_publications ADD CONSTRAINT FK_A72DFDF8BB649746 FOREIGN KEY (updated_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notes_publications ADD CONSTRAINT FK_A72DFDF8FDE969F2 FOREIGN KEY (deleted_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A72DFDF8E104C1D3 ON notes_publications (created_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A72DFDF8BB649746 ON notes_publications (updated_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A72DFDF8FDE969F2 ON notes_publications (deleted_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sliders ADD created_user_id INT DEFAULT NULL, ADD updated_user_id INT DEFAULT NULL, ADD deleted_user_id INT DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL, ADD deleted_at DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sliders ADD CONSTRAINT FK_85A59DB8E104C1D3 FOREIGN KEY (created_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sliders ADD CONSTRAINT FK_85A59DB8BB649746 FOREIGN KEY (updated_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sliders ADD CONSTRAINT FK_85A59DB8FDE969F2 FOREIGN KEY (deleted_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_85A59DB8E104C1D3 ON sliders (created_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_85A59DB8BB649746 ON sliders (updated_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_85A59DB8FDE969F2 ON sliders (deleted_user_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE actualites DROP FOREIGN KEY FK_75315B6DE104C1D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actualites DROP FOREIGN KEY FK_75315B6DBB649746
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actualites DROP FOREIGN KEY FK_75315B6DFDE969F2
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_75315B6DE104C1D3 ON actualites
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_75315B6DBB649746 ON actualites
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_75315B6DFDE969F2 ON actualites
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actualites DROP created_user_id, DROP updated_user_id, DROP deleted_user_id, DROP created_at, DROP updated_at, DROP deleted_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE banners DROP FOREIGN KEY FK_250F2568E104C1D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE banners DROP FOREIGN KEY FK_250F2568BB649746
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE banners DROP FOREIGN KEY FK_250F2568FDE969F2
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_250F2568E104C1D3 ON banners
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_250F2568BB649746 ON banners
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_250F2568FDE969F2 ON banners
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE banners DROP created_user_id, DROP updated_user_id, DROP deleted_user_id, DROP created_at, DROP updated_at, DROP deleted_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notes_publications DROP FOREIGN KEY FK_A72DFDF8E104C1D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notes_publications DROP FOREIGN KEY FK_A72DFDF8BB649746
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notes_publications DROP FOREIGN KEY FK_A72DFDF8FDE969F2
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_A72DFDF8E104C1D3 ON notes_publications
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_A72DFDF8BB649746 ON notes_publications
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_A72DFDF8FDE969F2 ON notes_publications
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notes_publications DROP created_user_id, DROP updated_user_id, DROP deleted_user_id, DROP created_at, DROP updated_at, DROP deleted_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sliders DROP FOREIGN KEY FK_85A59DB8E104C1D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sliders DROP FOREIGN KEY FK_85A59DB8BB649746
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sliders DROP FOREIGN KEY FK_85A59DB8FDE969F2
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_85A59DB8E104C1D3 ON sliders
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_85A59DB8BB649746 ON sliders
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_85A59DB8FDE969F2 ON sliders
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sliders DROP created_user_id, DROP updated_user_id, DROP deleted_user_id, DROP created_at, DROP updated_at, DROP deleted_at
        SQL);
    }
}
