<?php declare(strict_types=1);

namespace App\Controller\Document;

use App\Authentication\AuthenticationContext;
use App\Controller\AppController;
use DateTimeImmutable;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateDocument;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentName;
use Star\Component\DomainEvent\Messaging\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

final class DocumentNew extends AppController
{
    /**
     * @Route(name="document_new", path="/documents", methods={"POST"})
     *
     * @param Request $request
     * @param CommandBus $bus
     * @param TranslatorInterface $translator
     * @param AuthenticationContext $context
     * @return Response
     */
    public function __invoke(
        Request $request,
        CommandBus $bus,
        TranslatorInterface $translator,
        AuthenticationContext $context
    ): Response {
        try {
            $name = new DocumentName($request->get('document_type_name'));
        } catch (Throwable $throwable) {
            $this->addFlashException(
                $translator->trans('flash.document_type_name.invalid', [], 'flash'),
                $throwable
            );
            return $this->redirect($this->generateUrl('dashboard'));
        }

        $documentId = DocumentId::random();
        $bus->dispatchCommand(
            new CreateDocument(
                $documentId,
                $name,
                $context->getLoggedMember(),
                new DateTimeImmutable()
            )
        );

        $this->addFlashSuccess(
            $translator->trans(
                'flash.success.document_type_created',
                ['%template%' => $name->toSerializableString()],
                'flash'
            )
        );

        return $this->redirect($this->generateUrl('document_design', ['id' => $documentId->toString()]));
    }
}
