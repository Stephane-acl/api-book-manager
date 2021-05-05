<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210223215220 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE author ADD library_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE author ADD CONSTRAINT FK_BDAFD8C8FE2541D7 FOREIGN KEY (library_id) REFERENCES library (id)');
        $this->addSql('CREATE INDEX IDX_BDAFD8C8FE2541D7 ON author (library_id)');
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331F675F31B');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331F675F31B FOREIGN KEY (author_id) REFERENCES author (id)');
        $this->addSql('ALTER TABLE category ADD library_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1FE2541D7 FOREIGN KEY (library_id) REFERENCES library (id)');
        $this->addSql('CREATE INDEX IDX_64C19C1FE2541D7 ON category (library_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE author DROP FOREIGN KEY FK_BDAFD8C8FE2541D7');
        $this->addSql('DROP INDEX IDX_BDAFD8C8FE2541D7 ON author');
        $this->addSql('ALTER TABLE author DROP library_id');
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331F675F31B');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331F675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1FE2541D7');
        $this->addSql('DROP INDEX IDX_64C19C1FE2541D7 ON category');
        $this->addSql('ALTER TABLE category DROP library_id');
    }
}
