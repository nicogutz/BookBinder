<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230510135651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(file_get_contents(__DIR__.'/dumps/authors.sql'));
        $this->addSql(file_get_contents(__DIR__.'/dumps/book_1.sql'));
        $this->addSql(file_get_contents(__DIR__.'/dumps/book_2.sql'));
        $this->addSql(file_get_contents(__DIR__.'/dumps/book_3.sql'));
        $this->addSql(file_get_contents(__DIR__.'/dumps/book_author.sql'));
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM book_author');
        $this->addSql('DELETE FROM book');
        $this->addSql('DELETE FROM author');
    }
}
