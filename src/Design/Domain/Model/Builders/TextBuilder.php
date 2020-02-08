<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Builders;

use Star\Component\Document\Design\Domain\Model\DocumentSchema;

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
