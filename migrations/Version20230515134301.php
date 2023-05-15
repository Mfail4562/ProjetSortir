<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230515134301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD user_campus_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AFBDD805 FOREIGN KEY (user_campus_id) REFERENCES campus (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649AFBDD805 ON user (user_campus_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649AFBDD805');
        $this->addSql('DROP INDEX IDX_8D93D649AFBDD805 ON user');
        $this->addSql('ALTER TABLE user DROP user_campus_id');
    }
}
