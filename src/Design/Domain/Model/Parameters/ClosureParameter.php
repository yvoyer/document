<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Parameters;

use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\Document\Design\Domain\Model\Schema\DocumentSchema;

final class ClosureParameter implements PropertyParameter
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var \Closure
     */
    private $closure;

    public function __construct(string $name, \Closure $closure)
    {
        $this->name = $name;
        $this->closure = $closure;
    }

    public function toParameterData(): ParameterData
    {
        return new ParameterData($this->name, self::class, [$this->name, $this->closure]);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function onAdd(DocumentSchema $schema): void
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    public static function fromParameterData(ParameterData $data): PropertyParameter
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }
}
