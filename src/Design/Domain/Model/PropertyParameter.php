<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Design\Domain\Model\Parameters\ParameterData;
use Star\Component\Document\Design\Domain\Model\Schema\DocumentSchema;

interface PropertyParameter
{
    public function toParameterData(): ParameterData;

    public function getName(): string;

    public function onAdd(DocumentSchema $schema): void;

    public static function fromParameterData(ParameterData $data): PropertyParameter;
}
