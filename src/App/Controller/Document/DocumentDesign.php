<?php declare(strict_types=1);

namespace App\Controller\Document;

use App\Controller\AppController;
use Star\Component\Document\Design\Domain\Messaging\Query\FindSchemaForDocuments;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Infrastructure\Templating\SymfonyForm\DocumentDesignType;
use Star\Component\DomainEvent\Messaging\QueryBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DocumentDesign extends AppController
{
    /**
     * @Route(name="document_design", path="/documents/{id}", methods={"GET", "PUT"})
     *
     * @param string $id
     * @param QueryBus $bus
     * @return Response
     */
    public function __invoke(string $id, QueryBus $bus, Request $request): Response
    {
        $form = $this->createForm(DocumentDesignType::class);
        $bus->dispatchQuery(
            $query = new FindSchemaForDocuments(
                $request->getLocale(),
                $documentId = DocumentId::fromString($id)
            )
        );

        return $this->render(
            'Document/design.html.twig',
            [
                'document' => $query->getSingleSchema($documentId),
                'form' => $form->createView(),
            ]
        );
    }
}
