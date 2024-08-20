<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240820152937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account CHANGE roles roles ENUM(\'sitter\', \'parent\', \'admin\')');
        $this->addSql('ALTER TABLE sitter_availability CHANGE day_of_week day_of_week ENUM(\'Lundi\', \'Mardi\', \'Mercredi\', \'Jeudi\', \'Vendredi\', \'Samedi\', \'Dimanche\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account CHANGE roles roles VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sitter_availability CHANGE day_of_week day_of_week VARCHAR(255) DEFAULT NULL');
    }
}
