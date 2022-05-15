<?php declare(strict_types=1);

namespace App\Mapping\Design;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="document")
 */
final class Document
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="id", type="string", length=36)
     *
     * @var string
     */
    private string $id;

    /**
     * @ORM\Column(name="structure", type="json")
     *
     * @var string
     */
    private string $structure;

    /**
     * @ORM\Column(name="created_at", type="date_immutable")
     *
     * @var string
     */
    private string $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Mapping\Membership\Member")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var string
     */
    private string $createdBy;

    /**
     * @ORM\Column(name="updated_at", type="date_immutable")
     *
     * @var string
     */
    private string $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Mapping\Membership\Member")
     * @ORM\JoinColumn(name="updated_by", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var string
     */
    private string $updateBy;
}
