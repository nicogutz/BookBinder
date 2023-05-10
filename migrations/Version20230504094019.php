<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230504094019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book ADD year INT NOT NULL, ADD page_number INT NOT NULL, ADD average_rating DOUBLE PRECISION DEFAULT NULL, ADD rating_count INT DEFAULT NULL, ADD price DOUBLE PRECISION NOT NULL, ADD genre VARCHAR(64) NOT NULL, DROP isbn10, CHANGE title title TINYTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book ADD isbn10 BIGINT NOT NULL, DROP year, DROP page_number, DROP average_rating, DROP rating_count, DROP price, DROP genre, CHANGE title title VARCHAR(255) NOT NULL');
    }
}
