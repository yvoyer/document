<?php declare(strict_types=1);

namespace App\Authentication\Port\InMemory;

use App\Authentication\AuthenticationContext;
use Star\Component\Document\Membership\Domain\Model\MemberId;

final class NullObjectContext implements AuthenticationContext
{
    private MemberId $loggedUser;

    public function __construct()
    {
        $this->loggedUser = MemberId::asUUid();
    }

    public function getLoggedMember(): MemberId
    {
        return $this->loggedUser;
    }
}
