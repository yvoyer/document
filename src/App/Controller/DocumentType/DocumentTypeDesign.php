<?php declare(strict_types=1);

namespace App\Controller\DocumentType;

use App\Controller\AppController;
use Star\Component\Document\Design\Domain\Messaging\Query\FindSchemaForDocumentTypes;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Infrastructure\Templating\SymfonyForm\DocumentDesignType;
use Star\Component\DomainEvent\Messaging\QueryBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DocumentTypeDesign extends AppController
{
    /**
     * @Route(name="document_type_design", path="/document-types/{id}", methods={"GET", "PUT"})
     *
     * @param string $id
     * @param QueryBus $bus
     * @param Request $request
     * @return Response
     */
    public function __invoke(string $id, QueryBus $bus, Request $request): Response
    {
        $form = $this->createForm(DocumentDesignType::class);
        $bus->dispatchQuery(
            $query = new FindSchemaForDocumentTypes(
                $request->getLocale(),
                $typeId = DocumentTypeId::fromString($id)
            )
        );

        return $this->render(
            'DocumentType/design.html.twig',
            [
                'document_type' => $query->getSingleSchema($typeId),
                'form' => $form->createView(),
            ]
        );
    }
}
