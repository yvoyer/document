<?php declare(strict_types=1);

namespace App;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\Provider\SchemaProvider;
use Star\Component\Document\Design\Infrastructure\Persistence\Doctrine\DesignTableStore;
use Star\Component\Document\Membership\Infrastructure\Persistence\Doctrine\MembershipTableStore;

final class SchemaManager implements SchemaProvider
{
    const PK_PUBLIC = 'id';

    private const UUID_LENGTH = 40;

    public function createSchema(): Schema
    {
        $schema = new Schema();

        $this->createMemberTable($schema);
        $this->createDocumentTable($schema);

        return $schema;
    }

    private function createMemberTable(Schema $schema): void
    {
        $members = $schema->createTable(MembershipTableStore::MEMBER);
        $this->addPrimaryKey($members);

        $members->addColumn(
            'name',
            Types::STRING
        );

        $this->addAuditAtToTable($members);
    }

    private function createDocumentTable(Schema $schema): void
    {
        $documents = $schema->createTable(DesignTableStore::DOCUMENT);
        $this->addPrimaryKey($documents);

        $documents->addColumn(
            'name',
            Types::ARRAY // array to store translations
        );
        $documents->addColumn(
            'schema',
            Types::JSON
        );

        $this->addAuditAtToTable($documents);
        $this->addAuditByToTable($documents);
    }

    private function addPrimaryKey(Table $table): void
    {
        $table->addColumn(
            self::PK_PUBLIC,
            Types::STRING,
            [
                'length' => self::UUID_LENGTH,
            ]
        );
        $table->addUniqueConstraint([self::PK_PUBLIC]);
    }

    private function addAuditAtToTable(Table $table): void
    {
        $table->addColumn(
            'created_at',
            Types::DATETIME_IMMUTABLE
        );
        $table->addColumn(
            'updated_at',
            Types::DATETIME_IMMUTABLE
        );
    }

    private function addAuditByToTable(Table $table): void
    {
        $table->addColumn(
            'created_by',
            Types::STRING,
            [
                'length' => self::UUID_LENGTH,
            ]
        );
        $table->addColumn(
            'updated_by',
            Types::STRING,
            [
                'length' => self::UUID_LENGTH,
            ]
        );
        $table->addForeignKeyConstraint(
            MembershipTableStore::MEMBER,
            ['created_by'],
            ['id']
        );
        $table->addForeignKeyConstraint(
            MembershipTableStore::MEMBER,
            ['updated_by'],
            ['id']
        );
    }
}
