<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250727191431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, profile_image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE world (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, visibility VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_3A771143B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE world_user_role (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, world_id INT NOT NULL, role VARCHAR(255) NOT NULL, INDEX IDX_4C4EFBAA76ED395 (user_id), INDEX IDX_4C4EFBA8925311C (world_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE world ADD CONSTRAINT FK_3A771143B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE world_user_role ADD CONSTRAINT FK_4C4EFBAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE world_user_role ADD CONSTRAINT FK_4C4EFBA8925311C FOREIGN KEY (world_id) REFERENCES world (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE world DROP FOREIGN KEY FK_3A771143B03A8386');
        $this->addSql('ALTER TABLE world_user_role DROP FOREIGN KEY FK_4C4EFBAA76ED395');
        $this->addSql('ALTER TABLE world_user_role DROP FOREIGN KEY FK_4C4EFBA8925311C');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE world');
        $this->addSql('DROP TABLE world_user_role');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
