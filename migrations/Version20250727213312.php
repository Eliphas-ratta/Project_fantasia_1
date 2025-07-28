<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250727213312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_participant (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, entity_type VARCHAR(255) NOT NULL, entity_id INT NOT NULL, role VARCHAR(255) DEFAULT NULL, INDEX IDX_7C16B89171F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE faction_guild (faction_id INT NOT NULL, guild_id INT NOT NULL, INDEX IDX_483934794448F8DA (faction_id), INDEX IDX_483934795F2131EF (guild_id), PRIMARY KEY(faction_id, guild_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE faction_race (faction_id INT NOT NULL, race_id INT NOT NULL, INDEX IDX_7BB3BECA4448F8DA (faction_id), INDEX IDX_7BB3BECA6E59D40D (race_id), PRIMARY KEY(faction_id, race_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE faction_religion (faction_id INT NOT NULL, religion_id INT NOT NULL, INDEX IDX_963A284E4448F8DA (faction_id), INDEX IDX_963A284EB7850CBD (religion_id), PRIMARY KEY(faction_id, religion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE faction_technology (faction_id INT NOT NULL, technology_id INT NOT NULL, INDEX IDX_D08272A24448F8DA (faction_id), INDEX IDX_D08272A24235D463 (technology_id), PRIMARY KEY(faction_id, technology_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE guild_hero (guild_id INT NOT NULL, hero_id INT NOT NULL, INDEX IDX_B09BC8285F2131EF (guild_id), INDEX IDX_B09BC82845B0BCD (hero_id), PRIMARY KEY(guild_id, hero_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE guild_location (guild_id INT NOT NULL, location_id INT NOT NULL, INDEX IDX_3E1EBDD05F2131EF (guild_id), INDEX IDX_3E1EBDD064D218E (location_id), PRIMARY KEY(guild_id, location_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hero_location (hero_id INT NOT NULL, location_id INT NOT NULL, INDEX IDX_FB80386345B0BCD (hero_id), INDEX IDX_FB80386364D218E (location_id), PRIMARY KEY(hero_id, location_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hero_religion (hero_id INT NOT NULL, religion_id INT NOT NULL, INDEX IDX_B54B455145B0BCD (hero_id), INDEX IDX_B54B4551B7850CBD (religion_id), PRIMARY KEY(hero_id, religion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE magic_domain_hero (magic_domain_id INT NOT NULL, hero_id INT NOT NULL, INDEX IDX_AE6AA2C7C835E77F (magic_domain_id), INDEX IDX_AE6AA2C745B0BCD (hero_id), PRIMARY KEY(magic_domain_id, hero_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE magic_domain_race (magic_domain_id INT NOT NULL, race_id INT NOT NULL, INDEX IDX_25CB77EEC835E77F (magic_domain_id), INDEX IDX_25CB77EE6E59D40D (race_id), PRIMARY KEY(magic_domain_id, race_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE religion_location (religion_id INT NOT NULL, location_id INT NOT NULL, INDEX IDX_43116751B7850CBD (religion_id), INDEX IDX_4311675164D218E (location_id), PRIMARY KEY(religion_id, location_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_participant ADD CONSTRAINT FK_7C16B89171F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE faction_guild ADD CONSTRAINT FK_483934794448F8DA FOREIGN KEY (faction_id) REFERENCES faction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE faction_guild ADD CONSTRAINT FK_483934795F2131EF FOREIGN KEY (guild_id) REFERENCES guild (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE faction_race ADD CONSTRAINT FK_7BB3BECA4448F8DA FOREIGN KEY (faction_id) REFERENCES faction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE faction_race ADD CONSTRAINT FK_7BB3BECA6E59D40D FOREIGN KEY (race_id) REFERENCES race (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE faction_religion ADD CONSTRAINT FK_963A284E4448F8DA FOREIGN KEY (faction_id) REFERENCES faction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE faction_religion ADD CONSTRAINT FK_963A284EB7850CBD FOREIGN KEY (religion_id) REFERENCES religion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE faction_technology ADD CONSTRAINT FK_D08272A24448F8DA FOREIGN KEY (faction_id) REFERENCES faction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE faction_technology ADD CONSTRAINT FK_D08272A24235D463 FOREIGN KEY (technology_id) REFERENCES technology (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE guild_hero ADD CONSTRAINT FK_B09BC8285F2131EF FOREIGN KEY (guild_id) REFERENCES guild (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE guild_hero ADD CONSTRAINT FK_B09BC82845B0BCD FOREIGN KEY (hero_id) REFERENCES hero (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE guild_location ADD CONSTRAINT FK_3E1EBDD05F2131EF FOREIGN KEY (guild_id) REFERENCES guild (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE guild_location ADD CONSTRAINT FK_3E1EBDD064D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hero_location ADD CONSTRAINT FK_FB80386345B0BCD FOREIGN KEY (hero_id) REFERENCES hero (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hero_location ADD CONSTRAINT FK_FB80386364D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hero_religion ADD CONSTRAINT FK_B54B455145B0BCD FOREIGN KEY (hero_id) REFERENCES hero (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hero_religion ADD CONSTRAINT FK_B54B4551B7850CBD FOREIGN KEY (religion_id) REFERENCES religion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE magic_domain_hero ADD CONSTRAINT FK_AE6AA2C7C835E77F FOREIGN KEY (magic_domain_id) REFERENCES magic_domain (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE magic_domain_hero ADD CONSTRAINT FK_AE6AA2C745B0BCD FOREIGN KEY (hero_id) REFERENCES hero (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE magic_domain_race ADD CONSTRAINT FK_25CB77EEC835E77F FOREIGN KEY (magic_domain_id) REFERENCES magic_domain (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE magic_domain_race ADD CONSTRAINT FK_25CB77EE6E59D40D FOREIGN KEY (race_id) REFERENCES race (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE religion_location ADD CONSTRAINT FK_43116751B7850CBD FOREIGN KEY (religion_id) REFERENCES religion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE religion_location ADD CONSTRAINT FK_4311675164D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE creature ADD continent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE creature ADD CONSTRAINT FK_2A6C6AF4921F4C77 FOREIGN KEY (continent_id) REFERENCES continent (id)');
        $this->addSql('CREATE INDEX IDX_2A6C6AF4921F4C77 ON creature (continent_id)');
        $this->addSql('ALTER TABLE faction ADD continent_id INT DEFAULT NULL, ADD world_id INT NOT NULL');
        $this->addSql('ALTER TABLE faction ADD CONSTRAINT FK_83048B90921F4C77 FOREIGN KEY (continent_id) REFERENCES continent (id)');
        $this->addSql('ALTER TABLE faction ADD CONSTRAINT FK_83048B908925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('CREATE INDEX IDX_83048B90921F4C77 ON faction (continent_id)');
        $this->addSql('CREATE INDEX IDX_83048B908925311C ON faction (world_id)');
        $this->addSql('ALTER TABLE guild ADD world_id INT NOT NULL');
        $this->addSql('ALTER TABLE guild ADD CONSTRAINT FK_75407DAB8925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('CREATE INDEX IDX_75407DAB8925311C ON guild (world_id)');
        $this->addSql('ALTER TABLE hero ADD world_id INT NOT NULL, ADD race_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hero ADD CONSTRAINT FK_51CE6E868925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('ALTER TABLE hero ADD CONSTRAINT FK_51CE6E866E59D40D FOREIGN KEY (race_id) REFERENCES race (id)');
        $this->addSql('CREATE INDEX IDX_51CE6E868925311C ON hero (world_id)');
        $this->addSql('CREATE INDEX IDX_51CE6E866E59D40D ON hero (race_id)');
        $this->addSql('ALTER TABLE location ADD continent_id INT DEFAULT NULL, ADD world_id INT NOT NULL');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB921F4C77 FOREIGN KEY (continent_id) REFERENCES continent (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB8925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('CREATE INDEX IDX_5E9E89CB921F4C77 ON location (continent_id)');
        $this->addSql('CREATE INDEX IDX_5E9E89CB8925311C ON location (world_id)');
        $this->addSql('ALTER TABLE magic_spell ADD domain_id INT DEFAULT NULL, ADD world_id INT NOT NULL');
        $this->addSql('ALTER TABLE magic_spell ADD CONSTRAINT FK_68DF84AA115F0EE5 FOREIGN KEY (domain_id) REFERENCES magic_domain (id)');
        $this->addSql('ALTER TABLE magic_spell ADD CONSTRAINT FK_68DF84AA8925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('CREATE INDEX IDX_68DF84AA115F0EE5 ON magic_spell (domain_id)');
        $this->addSql('CREATE INDEX IDX_68DF84AA8925311C ON magic_spell (world_id)');
        $this->addSql('ALTER TABLE race ADD world_id INT NOT NULL');
        $this->addSql('ALTER TABLE race ADD CONSTRAINT FK_DA6FBBAF8925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('CREATE INDEX IDX_DA6FBBAF8925311C ON race (world_id)');
        $this->addSql('ALTER TABLE religion ADD world_id INT NOT NULL');
        $this->addSql('ALTER TABLE religion ADD CONSTRAINT FK_1055F4F98925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('CREATE INDEX IDX_1055F4F98925311C ON religion (world_id)');
        $this->addSql('ALTER TABLE technology ADD world_id INT NOT NULL');
        $this->addSql('ALTER TABLE technology ADD CONSTRAINT FK_F463524D8925311C FOREIGN KEY (world_id) REFERENCES world (id)');
        $this->addSql('CREATE INDEX IDX_F463524D8925311C ON technology (world_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_participant DROP FOREIGN KEY FK_7C16B89171F7E88B');
        $this->addSql('ALTER TABLE faction_guild DROP FOREIGN KEY FK_483934794448F8DA');
        $this->addSql('ALTER TABLE faction_guild DROP FOREIGN KEY FK_483934795F2131EF');
        $this->addSql('ALTER TABLE faction_race DROP FOREIGN KEY FK_7BB3BECA4448F8DA');
        $this->addSql('ALTER TABLE faction_race DROP FOREIGN KEY FK_7BB3BECA6E59D40D');
        $this->addSql('ALTER TABLE faction_religion DROP FOREIGN KEY FK_963A284E4448F8DA');
        $this->addSql('ALTER TABLE faction_religion DROP FOREIGN KEY FK_963A284EB7850CBD');
        $this->addSql('ALTER TABLE faction_technology DROP FOREIGN KEY FK_D08272A24448F8DA');
        $this->addSql('ALTER TABLE faction_technology DROP FOREIGN KEY FK_D08272A24235D463');
        $this->addSql('ALTER TABLE guild_hero DROP FOREIGN KEY FK_B09BC8285F2131EF');
        $this->addSql('ALTER TABLE guild_hero DROP FOREIGN KEY FK_B09BC82845B0BCD');
        $this->addSql('ALTER TABLE guild_location DROP FOREIGN KEY FK_3E1EBDD05F2131EF');
        $this->addSql('ALTER TABLE guild_location DROP FOREIGN KEY FK_3E1EBDD064D218E');
        $this->addSql('ALTER TABLE hero_location DROP FOREIGN KEY FK_FB80386345B0BCD');
        $this->addSql('ALTER TABLE hero_location DROP FOREIGN KEY FK_FB80386364D218E');
        $this->addSql('ALTER TABLE hero_religion DROP FOREIGN KEY FK_B54B455145B0BCD');
        $this->addSql('ALTER TABLE hero_religion DROP FOREIGN KEY FK_B54B4551B7850CBD');
        $this->addSql('ALTER TABLE magic_domain_hero DROP FOREIGN KEY FK_AE6AA2C7C835E77F');
        $this->addSql('ALTER TABLE magic_domain_hero DROP FOREIGN KEY FK_AE6AA2C745B0BCD');
        $this->addSql('ALTER TABLE magic_domain_race DROP FOREIGN KEY FK_25CB77EEC835E77F');
        $this->addSql('ALTER TABLE magic_domain_race DROP FOREIGN KEY FK_25CB77EE6E59D40D');
        $this->addSql('ALTER TABLE religion_location DROP FOREIGN KEY FK_43116751B7850CBD');
        $this->addSql('ALTER TABLE religion_location DROP FOREIGN KEY FK_4311675164D218E');
        $this->addSql('DROP TABLE event_participant');
        $this->addSql('DROP TABLE faction_guild');
        $this->addSql('DROP TABLE faction_race');
        $this->addSql('DROP TABLE faction_religion');
        $this->addSql('DROP TABLE faction_technology');
        $this->addSql('DROP TABLE guild_hero');
        $this->addSql('DROP TABLE guild_location');
        $this->addSql('DROP TABLE hero_location');
        $this->addSql('DROP TABLE hero_religion');
        $this->addSql('DROP TABLE magic_domain_hero');
        $this->addSql('DROP TABLE magic_domain_race');
        $this->addSql('DROP TABLE religion_location');
        $this->addSql('ALTER TABLE race DROP FOREIGN KEY FK_DA6FBBAF8925311C');
        $this->addSql('DROP INDEX IDX_DA6FBBAF8925311C ON race');
        $this->addSql('ALTER TABLE race DROP world_id');
        $this->addSql('ALTER TABLE faction DROP FOREIGN KEY FK_83048B90921F4C77');
        $this->addSql('ALTER TABLE faction DROP FOREIGN KEY FK_83048B908925311C');
        $this->addSql('DROP INDEX IDX_83048B90921F4C77 ON faction');
        $this->addSql('DROP INDEX IDX_83048B908925311C ON faction');
        $this->addSql('ALTER TABLE faction DROP continent_id, DROP world_id');
        $this->addSql('ALTER TABLE creature DROP FOREIGN KEY FK_2A6C6AF4921F4C77');
        $this->addSql('DROP INDEX IDX_2A6C6AF4921F4C77 ON creature');
        $this->addSql('ALTER TABLE creature DROP continent_id');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB921F4C77');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB8925311C');
        $this->addSql('DROP INDEX IDX_5E9E89CB921F4C77 ON location');
        $this->addSql('DROP INDEX IDX_5E9E89CB8925311C ON location');
        $this->addSql('ALTER TABLE location DROP continent_id, DROP world_id');
        $this->addSql('ALTER TABLE magic_spell DROP FOREIGN KEY FK_68DF84AA115F0EE5');
        $this->addSql('ALTER TABLE magic_spell DROP FOREIGN KEY FK_68DF84AA8925311C');
        $this->addSql('DROP INDEX IDX_68DF84AA115F0EE5 ON magic_spell');
        $this->addSql('DROP INDEX IDX_68DF84AA8925311C ON magic_spell');
        $this->addSql('ALTER TABLE magic_spell DROP domain_id, DROP world_id');
        $this->addSql('ALTER TABLE technology DROP FOREIGN KEY FK_F463524D8925311C');
        $this->addSql('DROP INDEX IDX_F463524D8925311C ON technology');
        $this->addSql('ALTER TABLE technology DROP world_id');
        $this->addSql('ALTER TABLE guild DROP FOREIGN KEY FK_75407DAB8925311C');
        $this->addSql('DROP INDEX IDX_75407DAB8925311C ON guild');
        $this->addSql('ALTER TABLE guild DROP world_id');
        $this->addSql('ALTER TABLE hero DROP FOREIGN KEY FK_51CE6E868925311C');
        $this->addSql('ALTER TABLE hero DROP FOREIGN KEY FK_51CE6E866E59D40D');
        $this->addSql('DROP INDEX IDX_51CE6E868925311C ON hero');
        $this->addSql('DROP INDEX IDX_51CE6E866E59D40D ON hero');
        $this->addSql('ALTER TABLE hero DROP world_id, DROP race_id');
        $this->addSql('ALTER TABLE religion DROP FOREIGN KEY FK_1055F4F98925311C');
        $this->addSql('DROP INDEX IDX_1055F4F98925311C ON religion');
        $this->addSql('ALTER TABLE religion DROP world_id');
    }
}
