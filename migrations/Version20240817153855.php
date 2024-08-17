<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240817153855 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account CHANGE roles roles ENUM(\'sitter\', \'parent\', \'admin\')');
        $this->addSql('ALTER TABLE sitter ADD account_id INT NOT NULL, ADD city_id INT NOT NULL');
        $this->addSql('ALTER TABLE sitter ADD CONSTRAINT FK_B972B3459B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE sitter ADD CONSTRAINT FK_B972B3458BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B972B3459B6B5FBA ON sitter (account_id)');
        $this->addSql('CREATE INDEX IDX_B972B3458BAC62AF ON sitter (city_id)');
        $this->addSql('ALTER TABLE sitter_availability ADD sitter_id INT DEFAULT NULL, CHANGE day_of_week day_of_week ENUM(\'Lundi\', \'Mardi\', \'Mercredi\', \'Jeudi\', \'Vendredi\', \'Samedi\', \'Dimanche\')');
        $this->addSql('ALTER TABLE sitter_availability ADD CONSTRAINT FK_1293227B61F367C9 FOREIGN KEY (sitter_id) REFERENCES sitter (id)');
        $this->addSql('CREATE INDEX IDX_1293227B61F367C9 ON sitter_availability (sitter_id)');
        $this->addSql('ALTER TABLE user_parent ADD city_id INT NOT NULL, ADD account_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_parent ADD CONSTRAINT FK_58DC7B728BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE user_parent ADD CONSTRAINT FK_58DC7B729B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('CREATE INDEX IDX_58DC7B728BAC62AF ON user_parent (city_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_58DC7B729B6B5FBA ON user_parent (account_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account CHANGE roles roles VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sitter DROP FOREIGN KEY FK_B972B3459B6B5FBA');
        $this->addSql('ALTER TABLE sitter DROP FOREIGN KEY FK_B972B3458BAC62AF');
        $this->addSql('DROP INDEX UNIQ_B972B3459B6B5FBA ON sitter');
        $this->addSql('DROP INDEX IDX_B972B3458BAC62AF ON sitter');
        $this->addSql('ALTER TABLE sitter DROP account_id, DROP city_id');
        $this->addSql('ALTER TABLE sitter_availability DROP FOREIGN KEY FK_1293227B61F367C9');
        $this->addSql('DROP INDEX IDX_1293227B61F367C9 ON sitter_availability');
        $this->addSql('ALTER TABLE sitter_availability DROP sitter_id, CHANGE day_of_week day_of_week VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_parent DROP FOREIGN KEY FK_58DC7B728BAC62AF');
        $this->addSql('ALTER TABLE user_parent DROP FOREIGN KEY FK_58DC7B729B6B5FBA');
        $this->addSql('DROP INDEX IDX_58DC7B728BAC62AF ON user_parent');
        $this->addSql('DROP INDEX UNIQ_58DC7B729B6B5FBA ON user_parent');
        $this->addSql('ALTER TABLE user_parent DROP city_id, DROP account_id');
    }
}
