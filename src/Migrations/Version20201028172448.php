<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201028172448 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE chapter (id INT AUTO_INCREMENT NOT NULL, fanfiction_id INT NOT NULL, added_at DATE NOT NULL, chapter INT NOT NULL, link VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, INDEX IDX_F981B52EC2DECEC (fanfiction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, fanfiction_id INT DEFAULT NULL, rating INT NOT NULL, commentary LONGTEXT NOT NULL, creation_date DATE NOT NULL, INDEX IDX_9474526CF675F31B (author_id), INDEX IDX_9474526CC2DECEC (fanfiction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fanfiction (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, language_id INT NOT NULL, title VARCHAR(255) NOT NULL, summary LONGTEXT DEFAULT NULL, cover_image VARCHAR(255) DEFAULT NULL, INDEX IDX_4331EDACF675F31B (author_id), INDEX IDX_4331EDAC82F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre_fanfiction (genre_id INT NOT NULL, fanfiction_id INT NOT NULL, INDEX IDX_3F5861894296D31F (genre_id), INDEX IDX_3F586189C2DECEC (fanfiction_id), PRIMARY KEY(genre_id, fanfiction_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, short VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags_fanfiction (tags_id INT NOT NULL, fanfiction_id INT NOT NULL, INDEX IDX_2188F14D8D7B4FB4 (tags_id), INDEX IDX_2188F14DC2DECEC (fanfiction_id), PRIMARY KEY(tags_id, fanfiction_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudonym VARCHAR(255) NOT NULL, user_image VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chapter ADD CONSTRAINT FK_F981B52EC2DECEC FOREIGN KEY (fanfiction_id) REFERENCES fanfiction (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CC2DECEC FOREIGN KEY (fanfiction_id) REFERENCES fanfiction (id)');
        $this->addSql('ALTER TABLE fanfiction ADD CONSTRAINT FK_4331EDACF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE fanfiction ADD CONSTRAINT FK_4331EDAC82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE genre_fanfiction ADD CONSTRAINT FK_3F5861894296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE genre_fanfiction ADD CONSTRAINT FK_3F586189C2DECEC FOREIGN KEY (fanfiction_id) REFERENCES fanfiction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tags_fanfiction ADD CONSTRAINT FK_2188F14D8D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tags_fanfiction ADD CONSTRAINT FK_2188F14DC2DECEC FOREIGN KEY (fanfiction_id) REFERENCES fanfiction (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE chapter DROP FOREIGN KEY FK_F981B52EC2DECEC');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CC2DECEC');
        $this->addSql('ALTER TABLE genre_fanfiction DROP FOREIGN KEY FK_3F586189C2DECEC');
        $this->addSql('ALTER TABLE tags_fanfiction DROP FOREIGN KEY FK_2188F14DC2DECEC');
        $this->addSql('ALTER TABLE genre_fanfiction DROP FOREIGN KEY FK_3F5861894296D31F');
        $this->addSql('ALTER TABLE fanfiction DROP FOREIGN KEY FK_4331EDAC82F1BAF4');
        $this->addSql('ALTER TABLE tags_fanfiction DROP FOREIGN KEY FK_2188F14D8D7B4FB4');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE fanfiction DROP FOREIGN KEY FK_4331EDACF675F31B');
        $this->addSql('DROP TABLE chapter');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE fanfiction');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE genre_fanfiction');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE tags_fanfiction');
        $this->addSql('DROP TABLE user');
    }
}
