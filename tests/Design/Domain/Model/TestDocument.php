<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model;

use Star\Component\Document\Design\Domain\Model\DocumentAggregate;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentName;
use function uniqid;

final class TestDocument extends DocumentAggregate
{
    public static function fixture(): self
    {
        return self::draft(DocumentId::random(), new DocumentName(uniqid('type-')));
    }
}
