<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220424084127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE listing CHANGE name name VARCHAR(150) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CB0048D45E237E06 ON listing (name)');
        $this->addSql('ALTER TABLE task CHANGE name name VARCHAR(150) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_CB0048D45E237E06 ON listing');
        $this->addSql('ALTER TABLE listing CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE task CHANGE name name VARCHAR(255) NOT NULL');
    }
}
