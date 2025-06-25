<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250625110909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE actualites (id INT AUTO_INCREMENT NOT NULL, cover_id INT DEFAULT NULL, titre VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_75315B6D922726E9 (cover_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE banners (id INT AUTO_INCREMENT NOT NULL, cover_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, titre VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, couleur VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_250F2568922726E9 (cover_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE notes_publications (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, resume VARCHAR(255) DEFAULT NULL, description LONGTEXT NOT NULL, lien VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE sliders (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, tittre VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, bouton VARCHAR(255) DEFAULT NULL, lien VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_85A59DB83DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actualites ADD CONSTRAINT FK_75315B6D922726E9 FOREIGN KEY (cover_id) REFERENCES files (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE banners ADD CONSTRAINT FK_250F2568922726E9 FOREIGN KEY (cover_id) REFERENCES files (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sliders ADD CONSTRAINT FK_85A59DB83DA5256D FOREIGN KEY (image_id) REFERENCES files (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE actualites DROP FOREIGN KEY FK_75315B6D922726E9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE banners DROP FOREIGN KEY FK_250F2568922726E9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sliders DROP FOREIGN KEY FK_85A59DB83DA5256D
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE actualites
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE banners
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE notes_publications
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE sliders
        SQL);
    }
}
