<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\App\Fixtures;

use DateTimeImmutable;
use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateProperty;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentOwner;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Types\StringType;

final class DocumentTypeFixture
{
    private DocumentTypeId $documentId;
    private ApplicationFixtureBuilder $builder;
    private DocumentOwner $owner;

    public function __construct(
        DocumentTypeId $documentId,
        ApplicationFixtureBuilder $builder
    ) {
        $this->documentId = $documentId;
        $this->builder = $builder;
    }

    public function withTextProperty(string $code): PropertyFixture
    {
        return $this->withProperty($code, new StringType());
    }

    public function getDocumentTypeId(): DocumentTypeId
    {
        return $this->documentId;
    }

    private function withProperty(string $code, PropertyType $type): PropertyFixture
    {
        $code = PropertyCode::fromString($code);

        $this->builder->doCommand(
            new CreateProperty(
                $this->documentId,
                $code,
                $nameObject = PropertyName::fromLocalizedString($code->toString(), 'en'), // todo parametrize
                $type,
                new DateTimeImmutable()
            )
        );

        return new PropertyFixture($this->documentId, $code, $this, $this->builder);
    }
}
