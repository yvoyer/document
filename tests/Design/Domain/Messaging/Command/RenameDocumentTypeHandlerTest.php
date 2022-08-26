<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Messaging\Command;

use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\Design\Builder\DocumentTypeBuilder;
use Star\Component\Document\Design\Domain\Messaging\Command\RenameDocumentType;
use Star\Component\Document\Design\Domain\Messaging\Command\RenameDocumentTypeHandler;
use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Model\DocumentTypeName;
use Star\Component\Document\Design\Domain\Model\Test\NullOwner;
use Star\Component\Document\Design\Infrastructure\Persistence\InMemory\DocumentTypeCollection;

final class RenameDocumentTypeHandlerTest extends TestCase
{
    public function test_something(): void
    {
        $type = DocumentTypeBuilder::startDocumentTypeFixture()
            ->getDocumentType();
        $old = $type->getName('en');

        $handler = new RenameDocumentTypeHandler(new DocumentTypeCollection($type));
        $handler(
            new RenameDocumentType(
                $type->getIdentity(),
                DocumentTypeName::fromLocalizedString('new', 'en'),
                AuditDateTime::fromNow(),
                new NullOwner()
            )
        );

        self::assertNotSame($old->toString(), $type->getName('en')->toString());
        self::assertSame('new', $type->getName('en')->toString());
    }
}
