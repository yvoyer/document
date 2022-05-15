<?php declare(strict_types=1);

namespace App\Authentication\Port\InMemory;

use App\Authentication\AuthenticationContext;
use DateTimeImmutable;
use Star\Component\Document\Membership\Domain\Model\MemberAggregate;
use Star\Component\Document\Membership\Domain\Model\MemberId;
use Star\Component\Document\Membership\Domain\Model\MemberRepository;
use Star\Component\Document\Membership\Domain\Model\Username;

final class NullObjectContext implements AuthenticationContext
{
    private MemberRepository $members;
    private MemberId $loggedUser;

    public function __construct(MemberRepository $members)
    {
        $this->members = $members;
        $this->loggedUser = MemberId::fromString('null-member');
    }

    public function getLoggedMember(): MemberId
    {
        if (! $this->members->isRegistered($this->loggedUser)) {
            $this->members->saveMember(
                MemberAggregate::registered(
                    $this->loggedUser,
                    Username::fromString('null-member'),
                    new DateTimeImmutable()
                )
            );
        };

        return $this->loggedUser;
    }
}
