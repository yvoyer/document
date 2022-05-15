<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\App\Fixtures;

use DateTimeImmutable;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateProperty;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentOwner;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Types\StringType;

final class DocumentFixture
{
    private DocumentId $documentId;
    private ApplicationFixtureBuilder $builder;
    private DocumentOwner $owner;

    public function __construct(
        DocumentId $documentId,
        ApplicationFixtureBuilder $builder,
        DocumentOwner $owner
    ) {
        $this->documentId = $documentId;
        $this->builder = $builder;
        $this->owner = $owner;
    }

    public function withTextProperty(string $name, string $locale): PropertyFixture
    {
        return $this->withProperty($name, $locale, new StringType());
    }

    public function getDocumentId(): DocumentId
    {
        return $this->documentId;
    }

    private function withProperty(string $name, string $locale, PropertyType $type): PropertyFixture
    {
        $this->builder->doCommand(
            new CreateProperty(
                $this->documentId,
                $nameObject = PropertyName::fromString($name, $locale),
                $type,
                $this->owner,
                new DateTimeImmutable()
            )
        );

        return new PropertyFixture($this->documentId, $nameObject, $this, $this->builder);
    }
}
