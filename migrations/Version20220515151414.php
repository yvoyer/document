<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220515151414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create of document and member';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE document (
            id VARCHAR(36) NOT NULL,
            structure JSON NOT NULL,
            created_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\',
            created_by VARCHAR(36) DEFAULT NULL,
            updated_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\',
            updated_by VARCHAR(36) DEFAULT NULL,
            INDEX IDX_D8698A76DE12AB56 (created_by),
            INDEX IDX_D8698A7616FE72E1 (updated_by),
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_translation (
            id INT AUTO_INCREMENT NOT NULL,
            field VARCHAR(25) NOT NULL,
            locale VARCHAR(10) NOT NULL,
            content LONGTEXT NOT NULL,
            object_id VARCHAR(36) DEFAULT NULL,
            INDEX IDX_36C07205232D562B (object_id),
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member (
            id VARCHAR(36) NOT NULL,
            name VARCHAR(255) NOT NULL,
            created_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\',
            updated_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\',
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE document 
            ADD CONSTRAINT FK_D8698A76DE12AB56 FOREIGN KEY (created_by) REFERENCES member (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document 
            ADD CONSTRAINT FK_D8698A7616FE72E1 FOREIGN KEY (updated_by) REFERENCES member (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE document_translation 
            ADD CONSTRAINT FK_36C07205232D562B FOREIGN KEY (object_id) REFERENCES document (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document_translation DROP FOREIGN KEY FK_36C07205232D562B');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76DE12AB56');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A7616FE72E1');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE document_translation');
        $this->addSql('DROP TABLE member');
    }
}
