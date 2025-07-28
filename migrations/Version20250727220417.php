<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250727220417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE faction_location (id INT AUTO_INCREMENT NOT NULL, faction_id INT NOT NULL, location_id INT NOT NULL, relation_type VARCHAR(255) DEFAULT NULL, INDEX IDX_D8F1557C4448F8DA (faction_id), INDEX IDX_D8F1557C64D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hero_faction (id INT AUTO_INCREMENT NOT NULL, hero_id INT NOT NULL, faction_id INT NOT NULL, relation_type VARCHAR(255) DEFAULT NULL, INDEX IDX_970048B745B0BCD (hero_id), INDEX IDX_970048B74448F8DA (faction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hero_guild (id INT AUTO_INCREMENT NOT NULL, hero_id INT NOT NULL, guild_id INT NOT NULL, joined_at DATETIME DEFAULT NULL, role VARCHAR(255) DEFAULT NULL, INDEX IDX_EA7F726C45B0BCD (hero_id), INDEX IDX_EA7F726C5F2131EF (guild_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hero_relation (id INT AUTO_INCREMENT NOT NULL, source_hero_id INT NOT NULL, target_hero_id INT NOT NULL, relation_type VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_C797F6E14D84B3A5 (source_hero_id), INDEX IDX_C797F6E1CF33B2A6 (target_hero_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE faction_location ADD CONSTRAINT FK_D8F1557C4448F8DA FOREIGN KEY (faction_id) REFERENCES faction (id)');
        $this->addSql('ALTER TABLE faction_location ADD CONSTRAINT FK_D8F1557C64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE hero_faction ADD CONSTRAINT FK_970048B745B0BCD FOREIGN KEY (hero_id) REFERENCES hero (id)');
        $this->addSql('ALTER TABLE hero_faction ADD CONSTRAINT FK_970048B74448F8DA FOREIGN KEY (faction_id) REFERENCES faction (id)');
        $this->addSql('ALTER TABLE hero_guild ADD CONSTRAINT FK_EA7F726C45B0BCD FOREIGN KEY (hero_id) REFERENCES hero (id)');
        $this->addSql('ALTER TABLE hero_guild ADD CONSTRAINT FK_EA7F726C5F2131EF FOREIGN KEY (guild_id) REFERENCES guild (id)');
        $this->addSql('ALTER TABLE hero_relation ADD CONSTRAINT FK_C797F6E14D84B3A5 FOREIGN KEY (source_hero_id) REFERENCES hero (id)');
        $this->addSql('ALTER TABLE hero_relation ADD CONSTRAINT FK_C797F6E1CF33B2A6 FOREIGN KEY (target_hero_id) REFERENCES hero (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE faction_location DROP FOREIGN KEY FK_D8F1557C4448F8DA');
        $this->addSql('ALTER TABLE faction_location DROP FOREIGN KEY FK_D8F1557C64D218E');
        $this->addSql('ALTER TABLE hero_faction DROP FOREIGN KEY FK_970048B745B0BCD');
        $this->addSql('ALTER TABLE hero_faction DROP FOREIGN KEY FK_970048B74448F8DA');
        $this->addSql('ALTER TABLE hero_guild DROP FOREIGN KEY FK_EA7F726C45B0BCD');
        $this->addSql('ALTER TABLE hero_guild DROP FOREIGN KEY FK_EA7F726C5F2131EF');
        $this->addSql('ALTER TABLE hero_relation DROP FOREIGN KEY FK_C797F6E14D84B3A5');
        $this->addSql('ALTER TABLE hero_relation DROP FOREIGN KEY FK_C797F6E1CF33B2A6');
        $this->addSql('DROP TABLE faction_location');
        $this->addSql('DROP TABLE hero_faction');
        $this->addSql('DROP TABLE hero_guild');
        $this->addSql('DROP TABLE hero_relation');
    }
}
