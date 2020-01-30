<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\State\Builder\StateBuilder;
use Star\Component\State\StateMetadata;

final class DocumentState extends StateMetadata
{
    public function __construct()
    {
        parent::__construct('draft');
    }

    public function isPublished(): bool
    {
        return $this->isInState('published');
    }

    public function publish(): DocumentState
    {
        return $this->transit('publish', 'document');
    }

    /**
     * Returns the state workflow configuration.
     *
     * @param StateBuilder $builder
     */
    protected function configure(StateBuilder $builder): void
    {
        $builder->allowTransition('publish', 'draft', 'published');
    }
}
