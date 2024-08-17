<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240817115602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE work (id INT AUTO_INCREMENT NOT NULL, parent_id INT NOT NULL, sitter_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_534E6880727ACA70 (parent_id), INDEX IDX_534E688061F367C9 (sitter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE work ADD CONSTRAINT FK_534E6880727ACA70 FOREIGN KEY (parent_id) REFERENCES user_parent (id)');
        $this->addSql('ALTER TABLE work ADD CONSTRAINT FK_534E688061F367C9 FOREIGN KEY (sitter_id) REFERENCES sitter (id)');
        $this->addSql('ALTER TABLE account CHANGE roles roles ENUM(\'sitter\', \'parent\', \'admin\')');
        $this->addSql('ALTER TABLE sitter_availability CHANGE day_of_week day_of_week ENUM(\'Lundi\', \'Mardi\', \'Mercredi\', \'Jeudi\', \'Vendredi\', \'Samedi\', \'Dimanche\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE work DROP FOREIGN KEY FK_534E6880727ACA70');
        $this->addSql('ALTER TABLE work DROP FOREIGN KEY FK_534E688061F367C9');
        $this->addSql('DROP TABLE work');
        $this->addSql('ALTER TABLE account CHANGE roles roles VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sitter_availability CHANGE day_of_week day_of_week VARCHAR(255) DEFAULT NULL');
    }
}
