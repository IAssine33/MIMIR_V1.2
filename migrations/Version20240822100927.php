<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240822100927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sitter ADD CONSTRAINT FK_B972B345A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B972B345A76ED395 ON sitter (user_id)');
        $this->addSql('ALTER TABLE sitter_availability CHANGE day_of_week day_of_week ENUM(\'Lundi\', \'Mardi\', \'Mercredi\', \'Jeudi\', \'Vendredi\', \'Samedi\', \'Dimanche\')');
        $this->addSql('ALTER TABLE user_parent ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_parent ADD CONSTRAINT FK_58DC7B72A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_58DC7B72A76ED395 ON user_parent (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sitter DROP FOREIGN KEY FK_B972B345A76ED395');
        $this->addSql('DROP INDEX UNIQ_B972B345A76ED395 ON sitter');
        $this->addSql('ALTER TABLE sitter_availability CHANGE day_of_week day_of_week VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_parent DROP FOREIGN KEY FK_58DC7B72A76ED395');
        $this->addSql('DROP INDEX UNIQ_58DC7B72A76ED395 ON user_parent');
        $this->addSql('ALTER TABLE user_parent DROP user_id');
    }
}
