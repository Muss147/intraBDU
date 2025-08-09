<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250801173622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE votes DROP INDEX UNIQ_518B7ACFECABD6E4, ADD INDEX IDX_518B7ACFECABD6E4 (votant_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE votes DROP INDEX IDX_518B7ACFECABD6E4, ADD UNIQUE INDEX UNIQ_518B7ACFECABD6E4 (votant_id)
        SQL);
    }
}
