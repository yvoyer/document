<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\App\Fixtures;

use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateProperty;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentTypeName;
use Star\Component\Document\Design\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Types\StringType;

final class DocumentTypeFixture
{
    private DocumentTypeId $documentId;
    private ApplicationFixtureBuilder $builder;

    public function __construct(
        DocumentTypeId $documentId,
        ApplicationFixtureBuilder $builder
    ) {
        $this->documentId = $documentId;
        $this->builder = $builder;
    }

    public function namedTo(string $name, string $locale): self
    {
        $this->builder->doCommand(
            new RenameDocumentType(
                $this->documentId,
                DocumentTypeName::fromLocalizedString($name, $locale),
                AuditDateTime::fromNow()
            )
        );

        return $this;
    }

    public function withTextProperty(string $name, string $locale): PropertyFixture
    {
        return $this->withProperty(
            $name,
            $locale,
            new StringType()
        );
    }

    public function getDocumentTypeId(): DocumentTypeId
    {
        return $this->documentId;
    }

    private function withProperty(string $name, string $locale, PropertyType $type): PropertyFixture
    {
        $code = PropertyCode::fromString($name);

        $this->builder->doCommand(
            new CreateProperty(
                $this->documentId,
                $code,
                PropertyName::fromLocalizedString($name, $locale),
                $type,
                AuditDateTime::fromNow()
            )
        );

        return new PropertyFixture($this->documentId, $code, $this, $this->builder);
    }
}
