<?php declare(strict_types=1);

namespace App\Mapping\Design;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="document_type_translation")
 * todo add unique on field/locale/object
 */
final class DocumentTypeTranslation extends ObjectTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="DocumentType")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var string
     */
    private string $objectId;
}
