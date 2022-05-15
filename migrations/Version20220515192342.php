<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220515192342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add property';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE document_property (
            id INT AUTO_INCREMENT NOT NULL,
            document_id VARCHAR(36) DEFAULT NULL,
            code VARCHAR(255) NOT NULL,
            type VARCHAR(255) NOT NULL,
            parameters JSON NOT NULL,
            constraints JSON NOT NULL,
            INDEX IDX_433ED87C33F7837 (document_id),
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_property_translation (
            id INT AUTO_INCREMENT NOT NULL,
            object_id INT DEFAULT NULL,
            field VARCHAR(25) NOT NULL,
            locale VARCHAR(10) NOT NULL,
            content LONGTEXT NOT NULL,
            INDEX IDX_CC02F769232D562B (object_id),
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE document_property 
            ADD CONSTRAINT FK_433ED87C33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_property_translation 
            ADD CONSTRAINT FK_CC02F769232D562B FOREIGN KEY (object_id) REFERENCES document_property (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document_property_translation DROP FOREIGN KEY FK_CC02F769232D562B');
        $this->addSql('DROP TABLE document_property');
        $this->addSql('DROP TABLE document_property_translation');
    }
}
