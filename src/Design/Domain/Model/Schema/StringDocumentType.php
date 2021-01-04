<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Schema;

use Assert\Assertion;
use Star\Component\Document\Design\Domain\Model\DocumentType;
use function trim;

final class StringDocumentType implements DocumentType
{
    /**
     * @var string
     */
    private $name;

    public function __construct(string $name)
    {
        $name = trim($name);
        Assertion::notEmpty($name, 'Document type name "%s" is empty, but non empty value was expected.');
        $this->name = $name;
    }

    public function toString(): string
    {
        return $this->name;
    }
}
