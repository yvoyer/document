<?php declare(strict_types=1);

namespace App\Controller\Document;

use App\Authentication\AuthenticationContext;
use App\Controller\AppController;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateDocument;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\Templating\NamedDocument;
use Star\Component\DomainEvent\Messaging\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

final class DocumentTypeNew extends AppController
{
    /**
     * @Route(name="document_type_new", path="/document-types", methods={"POST"})
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
            $type = new NamedDocument($request->get('document_type_name'));
        } catch (Throwable $throwable) {
            $this->addFlashException(
                $translator->trans('flash.document_type_name.invalid', [], 'flash'),
                $throwable
            );
            return $this->redirect($this->generateUrl('dashboard'));
        }

        $documentId = DocumentId::random();
        $bus->dispatchCommand(new CreateDocument($documentId, $type, $context->getLoggedMember()));

        $this->addFlashSuccess(
            $translator->trans(
                'flash.success.document_type_created',
                ['%template%' => $type->toSerializableString()],
                'flash'
            )
        );

        return $this->redirect($this->generateUrl('document_design', ['id' => $documentId->toString()]));
    }
}
