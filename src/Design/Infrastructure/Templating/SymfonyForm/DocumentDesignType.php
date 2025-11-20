<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Infrastructure\Templating\SymfonyForm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

final class DocumentDesignType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
#        $builder->
        parent::buildForm($builder, $options);
    }
}
