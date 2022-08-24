<?php declare(strict_types=1);

namespace App\Mapping\Design;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="document_type_property_translation")
 */
final class DocumentTypePropertyTranslation extends ObjectTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="DocumentTypeProperty")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var string
     */
    private string $objectId;
}
