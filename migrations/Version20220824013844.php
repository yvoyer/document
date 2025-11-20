<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220824013844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create document type schema';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE document_type (
            id VARCHAR(36) NOT NULL, 
            created_by VARCHAR(36) DEFAULT NULL, 
            updated_by VARCHAR(36) DEFAULT NULL, 
            structure JSON NOT NULL, 
            created_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', 
            updated_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', 
            INDEX IDX_2B6ADBBADE12AB56 (created_by), 
            INDEX IDX_2B6ADBBA16FE72E1 (updated_by), 
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_type_property (
            id INT AUTO_INCREMENT NOT NULL, 
            document_type_id VARCHAR(36) DEFAULT NULL, 
            code VARCHAR(255) NOT NULL, 
            type VARCHAR(255) NOT NULL, 
            parameters JSON NOT NULL, 
            constraints JSON NOT NULL, 
            INDEX IDX_61A29FA961232A4F (document_type_id), 
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_type_property_translation (
            id INT AUTO_INCREMENT NOT NULL, 
            object_id INT DEFAULT NULL, 
            field VARCHAR(25) NOT NULL, 
            locale VARCHAR(10) NOT NULL, 
            content LONGTEXT NOT NULL, 
            INDEX IDX_A3D13381232D562B (object_id), 
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_type_translation (
            id INT AUTO_INCREMENT NOT NULL, 
            object_id VARCHAR(36) DEFAULT NULL, 
            field VARCHAR(25) NOT NULL, 
            locale VARCHAR(10) NOT NULL, 
            content LONGTEXT NOT NULL, 
            INDEX IDX_9E12A12C232D562B (object_id), 
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member (
            id VARCHAR(36) NOT NULL, 
            name VARCHAR(255) NOT NULL, 
            created_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', 
            updated_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', 
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE document_type ADD CONSTRAINT FK_2B6ADBBADE12AB56 FOREIGN KEY (created_by) REFERENCES member (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_type ADD CONSTRAINT FK_2B6ADBBA16FE72E1 FOREIGN KEY (updated_by) REFERENCES member (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_type_property ADD CONSTRAINT FK_61A29FA961232A4F FOREIGN KEY (document_type_id) REFERENCES document_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_type_property_translation ADD CONSTRAINT FK_A3D13381232D562B FOREIGN KEY (object_id) REFERENCES document_type_property (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_type_translation ADD CONSTRAINT FK_9E12A12C232D562B FOREIGN KEY (object_id) REFERENCES document_type (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document_type_property DROP FOREIGN KEY FK_61A29FA961232A4F');
        $this->addSql('ALTER TABLE document_type_translation DROP FOREIGN KEY FK_9E12A12C232D562B');
        $this->addSql('ALTER TABLE document_type_property_translation DROP FOREIGN KEY FK_A3D13381232D562B');
        $this->addSql('ALTER TABLE document_type DROP FOREIGN KEY FK_2B6ADBBADE12AB56');
        $this->addSql('ALTER TABLE document_type DROP FOREIGN KEY FK_2B6ADBBA16FE72E1');
        $this->addSql('DROP TABLE document_type');
        $this->addSql('DROP TABLE document_type_property');
        $this->addSql('DROP TABLE document_type_property_translation');
        $this->addSql('DROP TABLE document_type_translation');
        $this->addSql('DROP TABLE member');
    }
}
