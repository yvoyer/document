<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Schema;

final class TextBuilder
{
    /**
     * @var DocumentSchema
     */
    private $schema;

    /**
     * @var SchemaBuilder
     */
    private $builder;

    public function __construct(DocumentSchema $schema, SchemaBuilder $builder)
    {
        $this->schema = $schema;
        $this->builder = $builder;
    }

    public function endProperty(): SchemaBuilder
    {
        return $this->builder;
    }
}
