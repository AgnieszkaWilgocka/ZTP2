<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220803120223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ztp_comments DROP FOREIGN KEY FK_9A4DCCF716A2B381');
        $this->addSql('DROP INDEX IDX_9A4DCCF716A2B381 ON ztp_comments');
        $this->addSql('ALTER TABLE ztp_comments DROP book_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ztp_comments ADD book_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ztp_comments ADD CONSTRAINT FK_9A4DCCF716A2B381 FOREIGN KEY (book_id) REFERENCES ztp_books (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9A4DCCF716A2B381 ON ztp_comments (book_id)');
    }
}
