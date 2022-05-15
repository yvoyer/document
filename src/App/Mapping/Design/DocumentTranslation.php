<?php declare(strict_types=1);

namespace App\Mapping\Design;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="document_translation")
 * todo add unique on field/locale/object
 */
final class DocumentTranslation extends ObjectTranslation
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Mapping\Design\Document")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var string
     */
    private string $objectId;
}
