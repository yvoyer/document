<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

interface RecordAction
{
    /**
     * @return string The explanation of the action
     */
    public function toHumanReadable(): string;

    /**
     * @param SchemaMetadata $schema
     * @param DocumentRecord $record
     */
    public function perform(SchemaMetadata $schema, DocumentRecord $record): void;
}
