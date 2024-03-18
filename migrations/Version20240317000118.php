<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240317000118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE package (id UUID NOT NULL, profile_id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DE686795CCFA12B8 ON package (profile_id)');
        $this->addSql('CREATE TABLE package_content (package_id UUID NOT NULL, content_id UUID NOT NULL, PRIMARY KEY(package_id, content_id))');
        $this->addSql('CREATE INDEX IDX_719E5525F44CABFF ON package_content (package_id)');
        $this->addSql('CREATE INDEX IDX_719E552584A0A3ED ON package_content (content_id)');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT FK_DE686795CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE package_content ADD CONSTRAINT FK_719E5525F44CABFF FOREIGN KEY (package_id) REFERENCES package (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE package_content ADD CONSTRAINT FK_719E552584A0A3ED FOREIGN KEY (content_id) REFERENCES content (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE package DROP CONSTRAINT FK_DE686795CCFA12B8');
        $this->addSql('ALTER TABLE package_content DROP CONSTRAINT FK_719E5525F44CABFF');
        $this->addSql('ALTER TABLE package_content DROP CONSTRAINT FK_719E552584A0A3ED');
        $this->addSql('DROP TABLE package');
        $this->addSql('DROP TABLE package_content');
    }
}
