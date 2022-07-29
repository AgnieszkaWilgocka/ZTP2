<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220716125910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favourites (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, book_id INT DEFAULT NULL, INDEX IDX_7F07C501F675F31B (author_id), INDEX IDX_7F07C50116A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ztp_books (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, INDEX IDX_3617750912469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book_tag (ztp_books_ztp_tags INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_F2F4CE15A98592D7 (ztp_books_ztp_tags), INDEX IDX_F2F4CE15BAD26311 (tag_id), PRIMARY KEY(ztp_books_ztp_tags, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ztp_categories (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', title VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ztp_tags (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ztp_users (id INT AUTO_INCREMENT NOT NULL, user_data_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_688FFA726FF8BF36 (user_data_id), UNIQUE INDEX email_idx (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ztp_users_data (id INT AUTO_INCREMENT NOT NULL, nick VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favourites ADD CONSTRAINT FK_7F07C501F675F31B FOREIGN KEY (author_id) REFERENCES ztp_users (id)');
        $this->addSql('ALTER TABLE favourites ADD CONSTRAINT FK_7F07C50116A2B381 FOREIGN KEY (book_id) REFERENCES ztp_books (id)');
        $this->addSql('ALTER TABLE ztp_books ADD CONSTRAINT FK_3617750912469DE2 FOREIGN KEY (category_id) REFERENCES ztp_categories (id)');
        $this->addSql('ALTER TABLE book_tag ADD CONSTRAINT FK_F2F4CE15A98592D7 FOREIGN KEY (ztp_books_ztp_tags) REFERENCES ztp_books (id)');
        $this->addSql('ALTER TABLE book_tag ADD CONSTRAINT FK_F2F4CE15BAD26311 FOREIGN KEY (tag_id) REFERENCES ztp_tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ztp_users ADD CONSTRAINT FK_688FFA726FF8BF36 FOREIGN KEY (user_data_id) REFERENCES ztp_users_data (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favourites DROP FOREIGN KEY FK_7F07C50116A2B381');
        $this->addSql('ALTER TABLE book_tag DROP FOREIGN KEY FK_F2F4CE15A98592D7');
        $this->addSql('ALTER TABLE ztp_books DROP FOREIGN KEY FK_3617750912469DE2');
        $this->addSql('ALTER TABLE book_tag DROP FOREIGN KEY FK_F2F4CE15BAD26311');
        $this->addSql('ALTER TABLE favourites DROP FOREIGN KEY FK_7F07C501F675F31B');
        $this->addSql('ALTER TABLE ztp_users DROP FOREIGN KEY FK_688FFA726FF8BF36');
        $this->addSql('DROP TABLE favourites');
        $this->addSql('DROP TABLE ztp_books');
        $this->addSql('DROP TABLE book_tag');
        $this->addSql('DROP TABLE ztp_categories');
        $this->addSql('DROP TABLE ztp_tags');
        $this->addSql('DROP TABLE ztp_users');
        $this->addSql('DROP TABLE ztp_users_data');
    }
}
