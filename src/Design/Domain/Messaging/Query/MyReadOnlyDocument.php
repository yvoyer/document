<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Query;

use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\Types\NullType;
use Star\Component\Document\Design\Domain\Model\Types\TypeData;
use function array_key_exists;
use function array_keys;

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

    /**
     * @return string[]
     */
    public function getProperties(): array
    {
        return array_keys($this->data);
    }

    public function getProperty(string $name): ReadOnlyPropertyType
    {
        $property = new NullType();
        if (array_key_exists($name, $this->data)) {
            $dataString = $this->data[$name];
            if (TypeData::isValidString($dataString)) {
                $property = TypeData::fromString($dataString)->createType();
            }
        }

        return new ReadOnlyPropertyType($property);
    }
}
