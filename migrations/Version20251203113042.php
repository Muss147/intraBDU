<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251203113042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE actualites ADD thumbnail_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE actualites ADD CONSTRAINT FK_75315B6DFDFF2E92 FOREIGN KEY (thumbnail_id) REFERENCES files (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_75315B6DFDFF2E92 ON actualites (thumbnail_id)');
        $this->addSql('ALTER TABLE incidents CHANGE etape etape VARCHAR(255) NOT NULL, CHANGE code code VARCHAR(255) NOT NULL, CHANGE region region VARCHAR(255) DEFAULT NULL, CHANGE rapporteur rapporteur VARCHAR(255) NOT NULL, CHANGE fonction fonction VARCHAR(255) NOT NULL, CHANGE telephone telephone VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE description description LONGTEXT NOT NULL, CHANGE nature_recup nature_recup VARCHAR(255) DEFAULT NULL, CHANGE consequences consequences LONGTEXT DEFAULT NULL, CHANGE solutions solutions JSON DEFAULT NULL, CHANGE corrections corrections LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE actualites DROP FOREIGN KEY FK_75315B6DFDFF2E92');
        $this->addSql('DROP INDEX UNIQ_75315B6DFDFF2E92 ON actualites');
        $this->addSql('ALTER TABLE actualites DROP thumbnail_id');
        $this->addSql('ALTER TABLE incidents CHANGE etape etape VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE code code VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE region region VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE rapporteur rapporteur VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE fonction fonction VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE telephone telephone VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nature_recup nature_recup VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE consequences consequences LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE solutions solutions LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_bin`, CHANGE corrections corrections LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
