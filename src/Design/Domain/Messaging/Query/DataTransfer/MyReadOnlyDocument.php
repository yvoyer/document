<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Query\DataTransfer;

use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentType;
use Star\Component\Document\Design\Domain\Model\Schema\StringDocumentType;
use Star\Component\Document\Design\Domain\Model\Types\NullType;
use Star\Component\Document\Design\Domain\Model\Types\TypeData;
use function array_key_exists;
use function array_keys;
use function is_string;

final class MyReadOnlyDocument
{
    /**
     * @var mixed[]
     */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getDocumentId(): string
    {
        return DocumentId::fromString($this->data['id'])->toString();
    }

    public function documentType(): DocumentType
    {
        return new StringDocumentType($this->data['type']);
    }

    public function getName(): string{
        return $this->documentType()->toString();
    }

    /**
     * @return string[]
     */
    public function getPublicProperties(): array
    {
        $otherProperties = $this->data;
        unset($otherProperties['id']); // readable with getDocumentId()

        return array_keys($otherProperties);
    }

    public function getPublicProperty(string $name): ReadOnlyPropertyType
    {
        $otherProperties = $this->data;
        unset($otherProperties['id']); // readable with getDocumentId()

        $property = new NullType();
        if (array_key_exists($name, $this->data)) {
            $dataString = $this->data[$name];
            if (is_string($dataString) && TypeData::isValidString($dataString)) {
                $property = TypeData::fromString($dataString)->createType();
            }
        }

        return new ReadOnlyPropertyType($name, $property);
    }
}
