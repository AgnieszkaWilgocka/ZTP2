<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220807174856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ztp_comments ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ztp_comments ADD CONSTRAINT FK_9A4DCCF7F675F31B FOREIGN KEY (author_id) REFERENCES ztp_users (id)');
        $this->addSql('CREATE INDEX IDX_9A4DCCF7F675F31B ON ztp_comments (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ztp_comments DROP FOREIGN KEY FK_9A4DCCF7F675F31B');
        $this->addSql('DROP INDEX IDX_9A4DCCF7F675F31B ON ztp_comments');
        $this->addSql('ALTER TABLE ztp_comments DROP author_id');
    }
}
