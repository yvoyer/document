<?php declare(strict_types=1);

namespace Star\Component\Document\Membership\Domain\Model;

use Ramsey\Uuid\Uuid;
use Star\Component\Document\Design\Domain\Model\DocumentOwner;
use Star\Component\Identity\StringIdentity;

final class MemberId extends StringIdentity implements DocumentOwner
{
    public function toString(): string
    {
        return parent::toString();
    }

    public function toSerializableString(): string
    {
        return $this->toString();
    }

    public static function asUUid(): self
    {
        return self::fromString(Uuid::uuid4()->toString());
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }
}
