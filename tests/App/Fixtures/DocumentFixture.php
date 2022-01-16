<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\App\Fixtures;

use Star\Component\Document\Design\Domain\Messaging\Command\CreateProperty;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Types\StringType;

final class DocumentFixture
{
    private DocumentId $documentId;
    private ApplicationFixtureBuilder $builder;

    public function __construct(DocumentId $documentId, ApplicationFixtureBuilder $builder)
    {
        $this->documentId = $documentId;
        $this->builder = $builder;
    }

    public function withTextProperty(string $name): PropertyFixture
    {
        return $this->withProperty($name, new StringType());
    }

    public function getDocumentId(): DocumentId
    {
        return $this->documentId;
    }

    private function withProperty(string $name, PropertyType $type): PropertyFixture
    {
        $command = new CreateProperty($this->documentId, $nameObject = PropertyName::fromString($name), $type);

        $this->builder->doCommand($command);

        return new PropertyFixture($this->documentId, $nameObject, $this, $this->builder);
    }
}
