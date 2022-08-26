<?php declare(strict_types=1);

namespace App\Mapping\Design;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
abstract class ObjectTranslation
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
     * @ORM\Column(name="field", type="string", length=25)
     *
     * @var string
     */
    private string $field;

    /**
     * @ORM\Column(name="locale", type="string", length=10)
     *
     * @var string
     */
    private string $locale;

    /**
     * @ORM\Column(name="content", type="text")
     *
     * @var string
     */
    private string $content;
}
