<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211182955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokedex_pokemon DROP FOREIGN KEY FK_BD0379D5372A5D14');
        $this->addSql('ALTER TABLE pokedex_pokemon DROP FOREIGN KEY FK_BD0379D52FE71C3E');
        $this->addSql('DROP TABLE pokedex_pokemon');
        $this->addSql('ALTER TABLE pokedex DROP FOREIGN KEY FK_6336F6A77E3C61F9');
        $this->addSql('DROP INDEX UNIQ_6336F6A77E3C61F9 ON pokedex');
        $this->addSql('ALTER TABLE pokedex ADD pokemon_id INT NOT NULL, ADD level INT NOT NULL, ADD strong INT NOT NULL, CHANGE owner_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE pokedex ADD CONSTRAINT FK_6336F6A7A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE pokedex ADD CONSTRAINT FK_6336F6A72FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('CREATE INDEX IDX_6336F6A7A76ED395 ON pokedex (user_id)');
        $this->addSql('CREATE INDEX IDX_6336F6A72FE71C3E ON pokedex (pokemon_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pokedex_pokemon (pokedex_id INT NOT NULL, pokemon_id INT NOT NULL, INDEX IDX_BD0379D5372A5D14 (pokedex_id), INDEX IDX_BD0379D52FE71C3E (pokemon_id), PRIMARY KEY(pokedex_id, pokemon_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE pokedex_pokemon ADD CONSTRAINT FK_BD0379D5372A5D14 FOREIGN KEY (pokedex_id) REFERENCES pokedex (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pokedex_pokemon ADD CONSTRAINT FK_BD0379D52FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pokedex DROP FOREIGN KEY FK_6336F6A7A76ED395');
        $this->addSql('ALTER TABLE pokedex DROP FOREIGN KEY FK_6336F6A72FE71C3E');
        $this->addSql('DROP INDEX IDX_6336F6A7A76ED395 ON pokedex');
        $this->addSql('DROP INDEX IDX_6336F6A72FE71C3E ON pokedex');
        $this->addSql('ALTER TABLE pokedex ADD owner_id INT NOT NULL, DROP user_id, DROP pokemon_id, DROP level, DROP strong');
        $this->addSql('ALTER TABLE pokedex ADD CONSTRAINT FK_6336F6A77E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6336F6A77E3C61F9 ON pokedex (owner_id)');
    }
}
