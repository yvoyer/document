<?php declare(strict_types=1);

namespace App\Mapping\Design;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="document_type_property")
 */
final class DocumentTypeProperty
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=11)
     *
     * @var int
     */
    private int $id;

    /**
     * @ORM\Column(name="code", type="string", length=255)
     *
     * @var string
     */
    private string $code;

    /**
     * @ORM\Column(name="type", type="string", length=255)
     *
     * @var string
     */
    private string $type;

    /**
     * @ORM\Column(name="parameters", type="json")
     *
     * @var string
     */
    private string $parameters;

    /**
     * @ORM\Column(name="constraints", type="json")
     *
     * @var string
     */
    private string $constraints;

    /**
     * @ORM\ManyToOne(targetEntity="DocumentType")
     * @ORM\JoinColumn(name="document_type_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var DocumentType
     */
    private DocumentType $document;
}