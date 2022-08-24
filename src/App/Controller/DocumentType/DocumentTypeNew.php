<?php declare(strict_types=1);

namespace App\Controller\DocumentType;

use App\Authentication\AuthenticationContext;
use App\Controller\AppController;
use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\Bridge\Validation\FlashableException;
use Star\Component\Document\Design\Domain\Messaging\Command\CreateDocumentType;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentName;
use Star\Component\DomainEvent\Messaging\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

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
            $name = DocumentName::fromLocalizedString(
                $request->get('document_type_name'),
                $request->getLocale()
            );
        } catch (FlashableException $throwable) {
            $this->addFlashException(
                $translator->trans('flash.document_type_name.invalid', [], 'flash'),
                $throwable
            );

            return $this->redirect($this->generateUrl('dashboard'));
        }

        $typeId = DocumentTypeId::random();
        $bus->dispatchCommand(
            new CreateDocumentType(
                $typeId,
                $name,
                $context->getLoggedMember(),
                AuditDateTime::fromNow()
            )
        );

        $this->addFlashSuccess(
            $translator->trans(
                'flash.success.document_type_created',
                ['%template%' => $name->toSerializableString()],
                'flash'
            )
        );

        return $this->redirect($this->generateUrl('document_type_design', ['id' => $typeId->toString()]));
    }
}
