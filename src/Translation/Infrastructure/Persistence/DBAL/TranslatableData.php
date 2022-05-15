<?php declare(strict_types=1);

namespace Star\Component\Document\Translation\Infrastructure\Persistence\DBAL;

trait TranslatableData
{
    protected function mergeTranslatableDataForCreation(
        string $field,
        string $content,
        string $locale,
        string $objectId
    ): array {
        return $this->mergeTranslatableDataForUpdate(
            $this->mergeTranslationCriteria(
                [],
                $field,
                $locale,
                $objectId
            ),
            $content
        );
    }

    /**
     * @param string[] $in
     * @param string $content
     * @return string[]
     */
    protected function mergeTranslatableDataForUpdate(
        array $in,
        string $content
    ): array {
        $in['content'] = $content;

        return $in;
    }

    /**
     * @param string[] $in
     * @param string $field
     * @param string $locale
     * @param string $objectId
     * @return string[]
     */
    protected function mergeTranslationCriteria(
        array $in,
        string $field,
        string $locale,
        string $objectId
    ): array {
        $in['field'] = $field;
        $in['locale'] = $locale;
        $in['object_id'] = $objectId;

        return $in;
    }
}
