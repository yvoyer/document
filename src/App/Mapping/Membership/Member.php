<?php declare(strict_types=1);

namespace App\Mapping\Membership;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="member")
 */
final class Member
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="string", length=36)
     *
     * @var string
     */
    private string $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @var string
     */
    private string $name;

    /**
     * @ORM\Column(name="created_at", type="date_immutable")
     *
     * @var string
     */
    private string $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="date_immutable")
     *
     * @var string
     */
    private string $updatedAt;
}
