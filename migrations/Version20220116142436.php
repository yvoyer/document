<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220116142436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE member (
            id VARCHAR(40) NOT NULL, 
            name VARCHAR(255) NOT NULL,
            created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
            updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
            CONSTRAINT UNIQ_70E4FA78BF396750 UNIQUE (id)) 
            DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        ');
        $this->addSql('CREATE TABLE document (
            id VARCHAR(40) NOT NULL, 
            name LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', 
            `schema` JSON NOT NULL,
            created_by VARCHAR(40) NOT NULL, 
            created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
            updated_by VARCHAR(40) NOT NULL, 
            updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
            CONSTRAINT UNIQ_D8698A76BF396750 UNIQUE (id), 
            INDEX IDX_D8698A76DE12AB56 (created_by), 
            INDEX IDX_D8698A7616FE72E1 (updated_by)) 
            DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        ');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76DE12AB56 FOREIGN KEY (created_by) REFERENCES member (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A7616FE72E1 FOREIGN KEY (updated_by) REFERENCES member (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76DE12AB56');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A7616FE72E1');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE document');
    }
}
