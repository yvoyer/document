<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\App\Fixtures;

use Star\Component\Document\Membership\Domain\Model\MemberId;

final class MembershipFixture
{
    private MemberId $memberId;
    private ApplicationFixtureBuilder $builder;

    public function __construct(
        MemberId $memberId,
        ApplicationFixtureBuilder $builder
    ) {
        $this->memberId = $memberId;
        $this->builder = $builder;
    }

    final public function getMemberId(): MemberId
    {
        return $this->memberId;
    }
}
