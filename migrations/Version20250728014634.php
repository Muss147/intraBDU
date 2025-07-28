<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250728014634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents DROP FOREIGN KEY FK_E65135D01AC671B9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents DROP FOREIGN KEY FK_E65135D0365BF48
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents DROP FOREIGN KEY FK_E65135D0A55629DC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents DROP FOREIGN KEY FK_E65135D0BCF5E72D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents ADD CONSTRAINT FK_E65135D01AC671B9 FOREIGN KEY (sous_processus_id) REFERENCES parametres (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents ADD CONSTRAINT FK_E65135D0365BF48 FOREIGN KEY (sous_categorie_id) REFERENCES parametres (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents ADD CONSTRAINT FK_E65135D0A55629DC FOREIGN KEY (processus_id) REFERENCES parametres (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents ADD CONSTRAINT FK_E65135D0BCF5E72D FOREIGN KEY (categorie_id) REFERENCES parametres (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents DROP FOREIGN KEY FK_E65135D0BCF5E72D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents DROP FOREIGN KEY FK_E65135D0365BF48
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents DROP FOREIGN KEY FK_E65135D0A55629DC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents DROP FOREIGN KEY FK_E65135D01AC671B9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents ADD CONSTRAINT FK_E65135D0BCF5E72D FOREIGN KEY (categorie_id) REFERENCES incidents_params (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents ADD CONSTRAINT FK_E65135D0365BF48 FOREIGN KEY (sous_categorie_id) REFERENCES incidents_params (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents ADD CONSTRAINT FK_E65135D0A55629DC FOREIGN KEY (processus_id) REFERENCES incidents_params (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE incidents ADD CONSTRAINT FK_E65135D01AC671B9 FOREIGN KEY (sous_processus_id) REFERENCES incidents_params (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
    }
}
