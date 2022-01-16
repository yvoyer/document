<?php declare(strict_types=1);

namespace App\Authentication;

use Star\Component\Document\Membership\Domain\Model\MemberId;

interface AuthenticationContext
{
    public function getLoggedMember(): MemberId;
}
