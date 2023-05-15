<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230515135206 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE travel ADD campus_organiser_id INT NOT NULL');
        $this->addSql('ALTER TABLE travel ADD CONSTRAINT FK_2D0B6BCEF2EF26CE FOREIGN KEY (campus_organiser_id) REFERENCES campus (id)');
        $this->addSql('CREATE INDEX IDX_2D0B6BCEF2EF26CE ON travel (campus_organiser_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE travel DROP FOREIGN KEY FK_2D0B6BCEF2EF26CE');
        $this->addSql('DROP INDEX IDX_2D0B6BCEF2EF26CE ON travel');
        $this->addSql('ALTER TABLE travel DROP campus_organiser_id');
    }
}
