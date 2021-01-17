<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210116152442 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE library (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, cpo VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, logo LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE book ADD library_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331FE2541D7 FOREIGN KEY (library_id) REFERENCES library (id)');
        $this->addSql('CREATE INDEX IDX_CBE5A331FE2541D7 ON book (library_id)');
        $this->addSql('ALTER TABLE user ADD library_id INT DEFAULT NULL, CHANGE first_name first_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649FE2541D7 FOREIGN KEY (library_id) REFERENCES library (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649FE2541D7 ON user (library_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331FE2541D7');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649FE2541D7');
        $this->addSql('DROP TABLE library');
        $this->addSql('DROP INDEX IDX_CBE5A331FE2541D7 ON book');
        $this->addSql('ALTER TABLE book DROP library_id');
        $this->addSql('DROP INDEX IDX_8D93D649FE2541D7 ON user');
        $this->addSql('ALTER TABLE user DROP library_id, CHANGE first_name first_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
