<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260125172429 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE blog_entry (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, release_year INT NOT NULL, description VARCHAR(255) NOT NULL, image_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE blog_entry_actor (blog_entry_id INT NOT NULL, actor_id INT NOT NULL, INDEX IDX_195FA9A1CE6B6D65 (blog_entry_id), INDEX IDX_195FA9A110DAF24A (actor_id), PRIMARY KEY (blog_entry_id, actor_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE blog_entry_actor ADD CONSTRAINT FK_195FA9A1CE6B6D65 FOREIGN KEY (blog_entry_id) REFERENCES blog_entry (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blog_entry_actor ADD CONSTRAINT FK_195FA9A110DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blog_entry_actor DROP FOREIGN KEY FK_195FA9A1CE6B6D65');
        $this->addSql('ALTER TABLE blog_entry_actor DROP FOREIGN KEY FK_195FA9A110DAF24A');
        $this->addSql('DROP TABLE actor');
        $this->addSql('DROP TABLE blog_entry');
        $this->addSql('DROP TABLE blog_entry_actor');
    }
}
