<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250624230942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE actions (id INT AUTO_INCREMENT NOT NULL, permission_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_548F1EFFED90CCA (permission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE actions_autorisation (id INT AUTO_INCREMENT NOT NULL, autorisation_id INT NOT NULL, action_id INT NOT NULL, checked TINYINT(1) DEFAULT NULL, INDEX IDX_8F9CE87452C5E836 (autorisation_id), INDEX IDX_8F9CE8749D32F035 (action_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE activity_log (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, type VARCHAR(255) NOT NULL, method VARCHAR(10) DEFAULT NULL, path VARCHAR(255) DEFAULT NULL, ip_address VARCHAR(15) NOT NULL, device VARCHAR(255) DEFAULT NULL, location VARCHAR(255) DEFAULT NULL, status VARCHAR(10) NOT NULL, response_code INT DEFAULT NULL, duration DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_FD06F647A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE autorisations (id INT AUTO_INCREMENT NOT NULL, permission_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_3AB39F8FFED90CCA (permission_id), INDEX IDX_3AB39F8FD60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE files (id INT AUTO_INCREMENT NOT NULL, created_user_id INT DEFAULT NULL, updated_user_id INT DEFAULT NULL, deleted_user_id INT DEFAULT NULL, filename VARCHAR(255) NOT NULL, alt VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_6354059E104C1D3 (created_user_id), INDEX IDX_6354059BB649746 (updated_user_id), INDEX IDX_6354059FDE969F2 (deleted_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE parametres (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, created_user_id INT DEFAULT NULL, updated_user_id INT DEFAULT NULL, deleted_user_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_1A79799D727ACA70 (parent_id), INDEX IDX_1A79799DE104C1D3 (created_user_id), INDEX IDX_1A79799DBB649746 (updated_user_id), INDEX IDX_1A79799DFDE969F2 (deleted_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE permissions (id INT AUTO_INCREMENT NOT NULL, created_user_id INT DEFAULT NULL, updated_user_id INT DEFAULT NULL, deleted_user_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, core TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_2DEDCC6FE104C1D3 (created_user_id), INDEX IDX_2DEDCC6FBB649746 (updated_user_id), INDEX IDX_2DEDCC6FFDE969F2 (deleted_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', expires_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, created_user_id INT DEFAULT NULL, updated_user_id INT DEFAULT NULL, deleted_user_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, all_access TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_B63E2EC7E104C1D3 (created_user_id), INDEX IDX_B63E2EC7BB649746 (updated_user_id), INDEX IDX_B63E2EC7FDE969F2 (deleted_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, role_id INT DEFAULT NULL, avatar_id INT DEFAULT NULL, created_user_id INT DEFAULT NULL, updated_user_id INT DEFAULT NULL, deleted_user_id INT DEFAULT NULL, nom_complet VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, last_login DATETIME DEFAULT NULL, activated TINYINT(1) DEFAULT NULL, activate_token VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), INDEX IDX_1483A5E9D60322AC (role_id), UNIQUE INDEX UNIQ_1483A5E986383B10 (avatar_id), INDEX IDX_1483A5E9E104C1D3 (created_user_id), INDEX IDX_1483A5E9BB649746 (updated_user_id), INDEX IDX_1483A5E9FDE969F2 (deleted_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actions ADD CONSTRAINT FK_548F1EFFED90CCA FOREIGN KEY (permission_id) REFERENCES permissions (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actions_autorisation ADD CONSTRAINT FK_8F9CE87452C5E836 FOREIGN KEY (autorisation_id) REFERENCES autorisations (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actions_autorisation ADD CONSTRAINT FK_8F9CE8749D32F035 FOREIGN KEY (action_id) REFERENCES actions (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log ADD CONSTRAINT FK_FD06F647A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE autorisations ADD CONSTRAINT FK_3AB39F8FFED90CCA FOREIGN KEY (permission_id) REFERENCES permissions (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE autorisations ADD CONSTRAINT FK_3AB39F8FD60322AC FOREIGN KEY (role_id) REFERENCES roles (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE files ADD CONSTRAINT FK_6354059E104C1D3 FOREIGN KEY (created_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE files ADD CONSTRAINT FK_6354059BB649746 FOREIGN KEY (updated_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE files ADD CONSTRAINT FK_6354059FDE969F2 FOREIGN KEY (deleted_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parametres ADD CONSTRAINT FK_1A79799D727ACA70 FOREIGN KEY (parent_id) REFERENCES parametres (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parametres ADD CONSTRAINT FK_1A79799DE104C1D3 FOREIGN KEY (created_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parametres ADD CONSTRAINT FK_1A79799DBB649746 FOREIGN KEY (updated_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parametres ADD CONSTRAINT FK_1A79799DFDE969F2 FOREIGN KEY (deleted_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permissions ADD CONSTRAINT FK_2DEDCC6FE104C1D3 FOREIGN KEY (created_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permissions ADD CONSTRAINT FK_2DEDCC6FBB649746 FOREIGN KEY (updated_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permissions ADD CONSTRAINT FK_2DEDCC6FFDE969F2 FOREIGN KEY (deleted_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE roles ADD CONSTRAINT FK_B63E2EC7E104C1D3 FOREIGN KEY (created_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE roles ADD CONSTRAINT FK_B63E2EC7BB649746 FOREIGN KEY (updated_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE roles ADD CONSTRAINT FK_B63E2EC7FDE969F2 FOREIGN KEY (deleted_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ADD CONSTRAINT FK_1483A5E9D60322AC FOREIGN KEY (role_id) REFERENCES roles (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ADD CONSTRAINT FK_1483A5E986383B10 FOREIGN KEY (avatar_id) REFERENCES files (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ADD CONSTRAINT FK_1483A5E9E104C1D3 FOREIGN KEY (created_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ADD CONSTRAINT FK_1483A5E9BB649746 FOREIGN KEY (updated_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ADD CONSTRAINT FK_1483A5E9FDE969F2 FOREIGN KEY (deleted_user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE actions DROP FOREIGN KEY FK_548F1EFFED90CCA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actions_autorisation DROP FOREIGN KEY FK_8F9CE87452C5E836
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE actions_autorisation DROP FOREIGN KEY FK_8F9CE8749D32F035
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE activity_log DROP FOREIGN KEY FK_FD06F647A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE autorisations DROP FOREIGN KEY FK_3AB39F8FFED90CCA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE autorisations DROP FOREIGN KEY FK_3AB39F8FD60322AC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE files DROP FOREIGN KEY FK_6354059E104C1D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE files DROP FOREIGN KEY FK_6354059BB649746
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE files DROP FOREIGN KEY FK_6354059FDE969F2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parametres DROP FOREIGN KEY FK_1A79799D727ACA70
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parametres DROP FOREIGN KEY FK_1A79799DE104C1D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parametres DROP FOREIGN KEY FK_1A79799DBB649746
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parametres DROP FOREIGN KEY FK_1A79799DFDE969F2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permissions DROP FOREIGN KEY FK_2DEDCC6FE104C1D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permissions DROP FOREIGN KEY FK_2DEDCC6FBB649746
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE permissions DROP FOREIGN KEY FK_2DEDCC6FFDE969F2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE roles DROP FOREIGN KEY FK_B63E2EC7E104C1D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE roles DROP FOREIGN KEY FK_B63E2EC7BB649746
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE roles DROP FOREIGN KEY FK_B63E2EC7FDE969F2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9D60322AC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users DROP FOREIGN KEY FK_1483A5E986383B10
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9E104C1D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9BB649746
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9FDE969F2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE actions
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE actions_autorisation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE activity_log
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE autorisations
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE files
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE parametres
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE permissions
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reset_password_request
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE roles
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
