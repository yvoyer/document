<?php declare(strict_types=1);

namespace Star\Component\Document\Membership\Domain\Model;

use Star\Component\Identity\Exception\EntityNotFoundException;

final class MemberNotFound extends EntityNotFoundException
{
}
