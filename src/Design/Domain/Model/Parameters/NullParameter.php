<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Parameters;

use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\Document\Design\Domain\Model\Schema\DocumentSchema;

final class NullParameter implements PropertyParameter
{
    /**
     * @var string
     */
    private $name;

    public function __construct(string $name = 'null')
    {
        $this->name = $name;
    }

    public function toParameterData(): ParameterData
    {
        return new ParameterData($this->getName(), self::class);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function onAdd(DocumentSchema $schema): void
    {
        // do nothing
    }

    public static function fromParameterData(ParameterData $data): PropertyParameter
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
