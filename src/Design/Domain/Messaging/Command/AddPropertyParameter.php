<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Command;

use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\Parameters\ParameterData;
use Star\Component\Document\Design\Domain\Model\PropertyCode;
use Star\Component\DomainEvent\Messaging\Command;

final class AddPropertyParameter implements Command
{
    private DocumentTypeId $typeId;
    private PropertyCode $code;
    private string $parameterName;
    private ParameterData $parameterData;
    private AuditDateTime $addedAt;

    public function __construct(
        DocumentTypeId $typeId,
        PropertyCode $code,
        string $parameterName,
        ParameterData $parameterData,
        AuditDateTime $addedAt
    ) {
        $this->typeId = $typeId;
        $this->code = $code;
        $this->parameterName = $parameterName;
        $this->parameterData = $parameterData;
        $this->addedAt = $addedAt;
    }

    public function typeId(): DocumentTypeId
    {
        return $this->typeId;
    }

    public function code(): PropertyCode
    {
        return $this->code;
    }

    public function parameterName(): string
    {
        return $this->parameterName;
    }

    public function parameterData(): ParameterData
    {
        return $this->parameterData;
    }

    final public function addedAt(): AuditDateTime
    {
        return $this->addedAt;
    }
}
