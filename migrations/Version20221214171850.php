<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221214171850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE proprietaires (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proprietaires_chaton (proprietaires_id INT NOT NULL, chaton_id INT NOT NULL, INDEX IDX_939FCA85710ED0A5 (proprietaires_id), INDEX IDX_939FCA85640066C9 (chaton_id), PRIMARY KEY(proprietaires_id, chaton_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE proprietaires_chaton ADD CONSTRAINT FK_939FCA85710ED0A5 FOREIGN KEY (proprietaires_id) REFERENCES proprietaires (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE proprietaires_chaton ADD CONSTRAINT FK_939FCA85640066C9 FOREIGN KEY (chaton_id) REFERENCES chaton (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE proprietaires_chaton DROP FOREIGN KEY FK_939FCA85710ED0A5');
        $this->addSql('ALTER TABLE proprietaires_chaton DROP FOREIGN KEY FK_939FCA85640066C9');
        $this->addSql('DROP TABLE proprietaires');
        $this->addSql('DROP TABLE proprietaires_chaton');
    }
}
